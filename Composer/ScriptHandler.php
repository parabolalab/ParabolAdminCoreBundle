<?php

namespace Parabol\AdminCoreBundle\Composer;

use Symfony\Component\ClassLoader\ClassCollectionLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\Event;
use Parabol\AdminCoreBundle\Manipulator\KernelManipulator;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Marcin Kalota <marcin@parabolalab.com>
 */
class ScriptHandler
{

    protected static $options = [
        'symfony-bin-dir' => 'bin',
        // 'bundles' => [], todo: add additional bundles from composer

    ];

    protected static $bundles = [
        'Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(this)',
        'WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle()',
        'Knp\Bundle\MenuBundle\KnpMenuBundle()',
        'A2lix\TranslationFormBundle\A2lixTranslationFormBundle()',
        'Ivory\CKEditorBundle\IvoryCKEditorBundle()',
    ];

    protected static $skeletons = [];


    protected static function getOptions(Event $event)
    {
        $options = array_merge(static::$options, $event->getComposer()->getPackage()->getExtra());

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');
        $options['vendor-dir'] = $event->getComposer()->getConfig()->get('vendor-dir');


        return $options;
    }

    protected static function hasDirectory(Event $event, $configName, $path, $actionName)
    {
        if (!is_dir($path)) {
            $event->getIO()->write(sprintf('The %s (%s) specified in composer.json was not found in %s, can not %s.', $configName, $path, getcwd(), $actionName));

            return false;
        }

        return true;
    }

    protected static function getPhp($includeArgs = true)
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find($includeArgs)) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    protected static function getPhpArguments()
    {
        $ini = null;
        $arguments = array();

        $phpFinder = new PhpExecutableFinder();
        if (method_exists($phpFinder, 'findArguments')) {
            $arguments = $phpFinder->findArguments();
        }

        if ($env = getenv('COMPOSER_ORIGINAL_INIS')) {
            $paths = explode(PATH_SEPARATOR, $env);
            $ini = array_shift($paths);
        } else {
            $ini = php_ini_loaded_file();
        }

        if ($ini) {
            $arguments[] = '--php-ini='.$ini;
        }

        return $arguments;
    }

    private static function removeDecoration($string)
    {
        return preg_replace("/\033\[[^m]*m/", '', $string);
    }

    protected static function executeCommand(Event $event, $consoleDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(static::getPhp(false));
        $phpArgs = implode(' ', array_map('escapeshellarg', static::getPhpArguments()));
        $console = escapeshellarg($consoleDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.($phpArgs ? ' '.$phpArgs : '').' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($event) { $event->getIO()->write($buffer, false); });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf("An error occurred when executing the \"%s\" command:\n\n%s\n\n%s", escapeshellarg($cmd), self::removeDecoration($process->getOutput()), self::removeDecoration($process->getErrorOutput())));
        }
    }


    /**
     * Asks if the new directory structure should be used, installs the structure if needed.
     *
     * @param Event $event
     */
    public static function install(Event $event)
    {

        $options = static::getOptions($event);

        static::prepareBundlesInstall();
        static::installBundles($event, $options);
        static::installSkeletons($event, $options);

    }

    protected static function prepareBundlesInstall()
    {
        $currentDir = dirname(__FILE__);

        foreach(glob('{' . $currentDir . '/../../*/*Bundle.php,' . $currentDir . '/../../*/Resources/skeleton/src/*/*/*Bundle.php' . '}', GLOB_BRACE) as $path)
        {
           if(strpos($path, '/skeleton/') !== false)
           {
                static::$skeletons[] = preg_replace('#^(.*\/skeleton\/).*$#','$1', $path);
           }

           $bundle = preg_replace('#^((.*\/src\/(.*))|(.*\/([^\/\.]+)))\.php$#','$3$5',$path) . '()'; 
           if(strpos($bundle, '/') === false) $bundle = 'Parabol\\' . strtr($bundle, ['Parabol' => '']) . '\\' . $bundle;
           else $bundle == strtr($bundle, ['/' => '\\']);

           static::$bundles[] = $bundle;

        }
    }


    protected static function installBundles(Event $event, $options)
    {

        static::executeCommand($event, $options['symfony-bin-dir'], 'parabol:add-bundle ' . implode(' ', static::$bundles), $options['process-timeout']);
    }


    protected static function installSkeletons(Event $event, $options)
    {

        $currentDir = dirname(__FILE__);

        if(isset(static::$skeletons[0]))
        {
            $fs = new Filesystem();
            $configPath = $options['symfony-app-dir'] . '/config/config.yml';
            $config = Yaml::parse(file_get_contents($configPath));

            foreach(static::$skeletons as $skeleton)
            {

                
                $fs->mirror($currentDir . '/../Resources/skeleton/', $currentDir . '/../../../../');

                $configSkeleton = $skeleton . '/app/config/config.yml';
                if(file_exists($configSkeleton))
                {
                    $newconfig = Yaml::parse(file_get_contents($configSkeleton));
                    $config = array_merge_recursive($config, $newconfig);
                }
            }

            if(isset($newconfig))
            {
                $dumped = Yaml::dump($config, 6);
                $dumped = preg_replace('/^([^\s#]+)/m',  "\n$1", $dumped);

                $fs->copy($configPath, $configPath .'.org.yml');
                $fs->dumpFile($options['symfony-app-dir'] . '/config/config_merged.yml', trim($dumped));
            }
        }
    }

}
