<?php

namespace Parabol\AdminCoreBundle\Composer;

use Symfony\Component\ClassLoader\ClassCollectionLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\Event;
use Parabol\BaseBundle\Component\Yaml\Yaml;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * @author Marcin Kalota <marcin@parabolalab.com>
 */
class ScriptHandler
{

    private static $kernel;

    protected static $options = [
        'symfony-bin-dir' => 'bin',
        // 'bundles' => [], todo: add additional bundles from composer

    ];

    protected static $bundles = [
        'Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle($this)',
        'WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle()',
        'Knp\Bundle\MenuBundle\KnpMenuBundle()',
        'A2lix\TranslationFormBundle\A2lixTranslationFormBundle()',
        'Ivory\CKEditorBundle\IvoryCKEditorBundle()',
        'Symfony\Bundle\AsseticBundle\AsseticBundle()'
    ];

    protected static $skeletons = [];

    protected static $bowerfilepaths = [];

    protected static $appParamseters = [
        'nodejs' => '/usr/local/bin/node',
        'uglifycss' => '/usr/local/bin/uglifycss',
        'uglifyjs' => '/usr/local/bin/uglifyjs',
        'node_paths' => ['/usr/local/lib/node_modules'],
    ];

    protected static function mergeArrayDuplicate($arr)
    {
        foreach($arr as $key => $value)
        {
            if(is_array($value)) $arr[$key] = static::mergeArrayDuplicate($value);
            else $arr = array_unique($arr);
        }

        return $arr;
    }


    protected static function getOptions(Event $event)
    {
        

        $options = array_merge(static::$options, $event->getComposer()->getPackage()->getExtra());

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');
        $options['vendor-dir'] = $event->getComposer()->getConfig()->get('vendor-dir');

        static::initKernel($options);
        $options['project-dir'] = substr(static::$kernel->getRootDir(),0,-3);


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

    protected static function executeCommand($consoleDir, $class, $cmd, $arguments, $timeout = 300)
    {
        
        $command = new $class($cmd);
        $command->setContainer(static::$kernel->getContainer());

        $input = new ArrayInput($arguments);
        $output = new ConsoleOutput();
        $command->run($input, $output);

    }

    private static function initKernel($options)
    {
        if(!static::$kernel)
        {
            require $options['vendor-dir'] . '/autoload.php';

            static::$kernel = new \AppKernel('dev', true);
            static::$kernel->boot();
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

        $configPath = $options['symfony-app-dir'] . '/config/config.yml';
        $config = Yaml::parse(file_get_contents($configPath));

        static::prepareBundlesInstall($options);
        // static::installBundles($options);
        static::installSkeletons($options);
        // static::mergeBowerFiles($options);
        // static::installBowerDepedencies($options);
        // static::addParameters($options);

    }

    protected static function mergeBowerFiles($options)
    {
        if(isset(static::$skeletons[0]))
        {
            $defaults = ['dependencies' => [], 'resolutions' => []];
            $mainBowerFile = $options['project-dir'] . 'bower.json';
            
            if(file_exists($mainBowerFile))
            {
                $mainBowerConfig = json_decode(file_get_contents($mainBowerFile), true);
            }

            if(!isset($mainBowerConfig) || !$mainBowerConfig) $mainBowerConfig = array_merge(['name' => strtolower(preg_replace('/[ ]+/','-', basename($options['project-dir']))), 'version' => '1.0' ], $defaults);

            foreach(static::$bowerfilepaths as $file)
            {
                $configPart = json_decode(file_get_contents($file), true);
                if($configPart)
                {
                    foreach ($defaults as $key => $value) {
                        if(isset($configPart[ $key ])) $mainBowerConfig[ $key ] = array_merge( $configPart[ $key ], $mainBowerConfig[ $key ] );    
                    }
                }
            }

            file_put_contents($mainBowerFile ,json_encode($mainBowerConfig, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }
    }

    protected static function prepareBundlesInstall($options)
    {
        if(in_array('Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle($this)', static::$bundles))
        {
            $admingeneratorpaths = glob($options['vendor-dir'] . '/*/*/AdmingeneratorGeneratorBundle.php');
            foreach($admingeneratorpaths as $path)
            {
                if(file_exists(dirname($path) . '/bower.json')) static::$bowerfilepaths[] = dirname($path) . '/bower.json';
            }
        }

        $currentDir = dirname(__FILE__);
        $dir = preg_replace('/\/[^\/]+\/[^\/]+$/','',$currentDir);

        foreach(glob('{' . $dir . '/*/*Bundle.php,' . $dir . '/*/Resources/skeleton/src/*/*/*Bundle.php' . '}', GLOB_BRACE) as $path)
        {
           if(strpos($path, '/skeleton/') !== false)
           {
                static::$skeletons[] = preg_replace('#^(.*\/skeleton\/).*$#','$1', $path);
           }

           $bundle = preg_replace('#^((.*\/src\/(.*))|(.*\/([^\/\.]+)))\.php$#','$3$5',$path); 

           if(strpos($bundle, '/') === false) $bundle = 'Parabol\\' . strtr($bundle, ['Parabol' => '']) . '\\' . $bundle;
           $bundle = strtr($bundle, ['/' => '\\']) . '()';

           // echo "$bundle\n";
           if(file_exists(dirname($path) . '/bower.json')) static::$bowerfilepaths[] = dirname($path) . '/bower.json';

           static::$bundles[] = $bundle;

        }

    }


    protected static function installBundles($options)
    {

        static::executeCommand($options['symfony-bin-dir'], \Parabol\AdminCoreBundle\Command\AddBundleCommand::class, 'parabol:add-bundle', [ 'bundles' => static::$bundles], $options['process-timeout']);
    }


    protected static function installSkeletons($options)
    {

        if(isset(static::$skeletons[0]))
        {

            $input = new ArrayInput([]);
            $output = new ConsoleOutput();
            $io = new SymfonyStyle($input, $output);

            $io->comment('Looking for files skeletons.');

            $fs = new Filesystem();
            $configPath = $options['symfony-app-dir'] . '/config/config.yml';
            $config = Yaml::parse(file_get_contents($configPath));

            foreach(static::$skeletons as $skeleton)
            {

                $io->writeLn('-> Copying files skeletons from <info>' . strtr($skeleton, [$options['project-dir'] => '']) . '</info>.');

                $fs->mirror($skeleton, $options['project-dir']);

                $configSkeleton = $skeleton . '/app/config/config.yml';
                if(file_exists($configSkeleton))
                {
                    $newconfig = Yaml::parse(file_get_contents($configSkeleton));
                    $io->writeLn('-> Merge config.yml skeleton from <info>' . strtr($skeleton, [$options['project-dir'] => '']) . '</info>.');
                    $config = array_merge_recursive($config, $newconfig);
                }
            }

            

            if(isset($newconfig))
            {
                $dumped = Yaml::dump($config, 6);
                $dumped = preg_replace('/^([^\s#]+)/m',  "\n$1", $dumped);

                $fs->copy($configPath, $configPath .'.org.yml');
                $fs->dumpFile($options['symfony-app-dir'] . '/config/config.yml', trim($dumped));

                $io->success("Skeletons has been copied and config.yml has been merged.");
            }
            else $io->success("Skeletons has been copied.");
        }
    }

    protected static function installBowerDepedencies($options)
    {
        if(file_exists($options['project-dir'] . 'bower.json')) static::executeCommand($options['symfony-bin-dir'], \Parabol\AdminCoreBundle\Command\InstallBowerDepedenciesCommand::class ,'parabol:install-bower-dep', ['list' => [ $options['project-dir'] . 'bower.json' ]], $options['process-timeout']);
    }

    protected static function addParameters($options)
    {
        $input = new ArrayInput([]);
        $output = new ConsoleOutput(\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL, true);
        $io = new SymfonyStyle($input, $output);
        $io->comment('Configure additional <info>aplication parameters</info>.');

        foreach (static::$appParamseters as $name => $value) {
            static::executeCommand($options['symfony-bin-dir'], \Parabol\AdminCoreBundle\Command\AddParameterCommand::class ,'parabol:add-parameter', ['name' => $name, 'default' => is_array($value) ? implode(',', $value) : $value, 'type' => is_array($value) ? 'array' : 'string'], $options['process-timeout']);
        }

    }

}
