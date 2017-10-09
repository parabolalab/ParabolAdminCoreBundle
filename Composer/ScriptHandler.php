<?php

namespace Parabol\AdminCoreBundle\Composer;

use Symfony\Component\ClassLoader\ClassCollectionLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\Event;
use Parabol\BaseBundle\Component\Yaml\Yaml;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\OutputInterface;

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
        'WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle',
        'Knp\Bundle\MenuBundle\KnpMenuBundle',
        'A2lix\TranslationFormBundle\A2lixTranslationFormBundle',
        'Ivory\CKEditorBundle\IvoryCKEditorBundle',
        'Symfony\Bundle\AsseticBundle\AsseticBundle',
        'Liip\ImagineBundle\LiipImagineBundle'
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

    protected static $io;


    protected static function getOptions(Event $event)
    {
        

        $options = array_merge(static::$options, $event->getComposer()->getPackage()->getExtra());

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');
        $options['vendor-dir'] = $event->getComposer()->getConfig()->get('vendor-dir');

        static::initKernel($options);
        $options['project-dir'] = substr(static::$kernel->getRootDir(),0,-3);


        return $options;
    }

    protected static function getIO()
    {
        if(!static::$io)
        {
            $input = new ArrayInput([]);
            $output = new ConsoleOutput(OutputInterface::VERBOSITY_NORMAL, true);
            static::$io = new SymfonyStyle($input, $output);
        }
        return static::$io;
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
        $command->setApplication(new Application(static::$kernel));
        if($command instanceof \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand) $command->setContainer(static::$kernel->getContainer());

        $input = new ArrayInput($arguments);
        $output = new ConsoleOutput( OutputInterface::VERBOSITY_NORMAL, true );
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

        static::prepareBundlesInstall($event, $options);
        static::installBundles($event, $options);
        static::installSkeletons($event, $options);
        static::mergeBowerFiles($event, $options);
        static::installBowerDepedencies($event, $options);
        static::addParameters($event, $options);
        static::createSchema($event, $options);
        static::loadFixturies($event, $options);

    }

    protected static function prepareBundlesInstall($event, $options)
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

        foreach(glob('{' . $dir . '/*/*Bundle.php,' . $dir . '/*/Resources/skeleton/*/src/*/*/*Bundle.php' . '}', GLOB_BRACE) as $path)
        {
           if(strpos($path, '/skeleton/') !== false)
           {
                static::$skeletons[] = preg_replace('#^(.*\/skeleton)\/.*$#','$1', $path);
           }
           else
           {
               if(file_exists(dirname($path) . '/Resources/config/bundles.yml'))
               {
                    $bundlesConfig = Yaml::parse(file_get_contents(dirname($path) . '/Resources/config/bundles.yml'));
                    if(isset($bundlesConfig['kernel']))
                    {
                        static::$bundles = array_merge(static::$bundles, $bundlesConfig['kernel']);
                    }
               }

               if(file_exists(dirname($path) . '/Resources/config/defaultParameters.yml'))
               {
                    $defaultParameters = Yaml::parse(file_get_contents(dirname($path) . '/Resources/config/defaultParameters.yml'));
                    static::$bundles = array_merge(static::$appParamseters, $defaultParameters);
                    
               }
            }

           $bundle = preg_replace('#^((.*\/src\/(.*))|(.*\/([^\/\.]+)))\.php$#','$3$5',$path); 

           if(strpos($bundle, '/') === false) $bundle = 'Parabol\\' . strtr($bundle, ['Parabol' => '']) . '\\' . $bundle;
           $bundle = strtr($bundle, ['/' => '\\']) . '()';



           // echo "$bundle\n";
           if(file_exists(dirname($path) . '/bower.json')) static::$bowerfilepaths[] = dirname($path) . '/bower.json';

           static::$bundles[] = $bundle;

        }

    }

    protected static function mergeBowerFiles($event, $options)
    {
        if(isset(static::$bowerfilepaths[0]))
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

    


    protected static function installBundles($event, $options)
    {
        foreach(static::$bundles as $bundle)
        {
            static::executeCommand($options['symfony-bin-dir'], \Parabol\AdminCoreBundle\Command\AddBundleCommand::class, 'parabol:add-bundle', [ '-r' => '', 'bundles' => [$bundle]], $options['process-timeout']);
        }
    }


    protected static function installSkeletons($event, $options)
    {

        
        if(isset(static::$skeletons[0]))
        {

           
            $fs = new Filesystem();
            $mergedContents = [];

            foreach(static::$skeletons as $skeleton)
            {

                if(file_exists($skeleton . '/create'))
                {
                    static::getIO()->writeLn('-> Creating files from <info>' . strtr($skeleton, [$options['project-dir'] => '']) . '/create</info> skeleton.');
                    $fs->mirror($skeleton . '/create', $options['project-dir']);
                }

                if(file_exists($skeleton . '/overwrite'))
                {
                    if($event->getIO()->askConfirmation('Do you want overwrite files skeletons from <info>' . strtr($skeleton, [$options['project-dir'] => '']) . '/overwrite</info>? [y/N] ', false))
                    {
                        static::getIO()->writeLn('-> Overwriting files from <info>' . strtr($skeleton, [$options['project-dir'] => '']) . '/overwrite</info> skeleton.');
                        $fs->mirror($skeleton . '/overwrite', $options['project-dir'], null, ['override' => true]);
                    }
                }

                if(file_exists($skeleton . '/merge'))
                {
                    if($event->getIO()->askConfirmation('Do you want merge files skeletons from <info>' . strtr($skeleton, [$options['project-dir'] => '']) . '/merge</info>? [y/N] ', false))
                    {

                        $directory = new \RecursiveDirectoryIterator($skeleton . '/merge', \FilesystemIterator::SKIP_DOTS);
                        $iterator = new \RecursiveIteratorIterator($directory);
                        
                        foreach ($iterator as $info) {
                          
                          $resourcePath = strtr($info->getPathname(), [$options['project-dir'] => '']);  

                          if (substr($info->getPathname(), -4) === '.yml') {
                                $path = str_replace($skeleton . '/merge/', '', $info->getPathname());
                                if(!isset($mergedContents[$path]))
                                {
                                    echo $options['project-dir'] . $path . "\n";
                                    if(file_exists($options['project-dir'] . $path)) $mergedContents[$path] = Yaml::parse(file_get_contents($options['project-dir'] . $path));
                                    else $mergedContents[$path] = [];
                                }

                                $fileContent = Yaml::parse(file_get_contents($info->getPathname()));

                                static::getIO()->writeLn("-> Merging <info>{$path}</info> with <info>{$resourcePath}</info>");
                                $mergedContents[$path] = Yaml::merge($fileContent, $mergedContents[$path]);


                          }
                          else
                          {
                             static::getIO()->writeLn("-> Skip <fg=red>{$resourcePath}</> merging skeleton is available only for yaml files");
                          }
                        }
                    }
                }
            }

            
            foreach($mergedContents as $path => $mergedContent)
            {
                $dumped = Yaml::dump($mergedContent, 3);
                $dumped = preg_replace('/^([^\s#]+)/m',  "\n$1", $dumped);
                static::getIO()->writeLn("-> Saving changes in file <info>{$path}</info>");
                $fs->copy($path, $path . '~');
                $fs->dumpFile($path, trim($dumped));
            }
           
            static::getIO()->success("Installing skeletons files has been done successfully.");

        }
    }

    protected static function installBowerDepedencies($event, $options)
    {
        if(file_exists($options['project-dir'] . '/bower.json') && $event->getIO()->askConfirmation('Do you want instal bower depedencies from <info>' . $options['project-dir'] . '/bower.json</info>? [y/N] ', false))
        {
            if(file_exists($options['project-dir'] . 'bower.json')) static::executeCommand($options['symfony-bin-dir'], \Parabol\AdminCoreBundle\Command\InstallBowerDepedenciesCommand::class ,'parabol:install-bower-dep', ['list' => [ $options['project-dir'] . 'bower.json' ]], $options['process-timeout']);
        }
    }

    protected static function addParameters($event, $options)
    {
        static::getIO()->comment('Configure additional <info>aplication parameters</info>.');

        static::$appParamseters['web_dir'] = isset($options['symfony-web-dir']) ? $options['symfony-web-dir'] : 'web';

        foreach (static::$appParamseters as $name => $value) {
            $params = ['name' => $name, 'default' => is_array($value) ? implode(',', $value) : $value, 'type' => is_array($value) ? 'array' : 'string'];
            if(preg_match('/password|secret/', $name)) $params['-n'] = '';
            static::executeCommand($options['symfony-bin-dir'], \Parabol\AdminCoreBundle\Command\AddParameterCommand::class ,'parabol:add-parameter', $params, $options['process-timeout']);
        }

    }

    protected static function createSchema($event, $options)
    {
        $io = static::getIO();

        do
        {
            $answer = $io->ask('Do you want create or update database schema? [n/c/u] ', 'n', function($value) use ($io)  { 
                if(in_array(strtolower($value), ['n', 'no', 'c', 'create', 'u', 'update']))
                {
                    return $value[0];
                } 
                else {
                   $io->writeln('<error>Please answer no, n, create, c, update or u.</error>');
                   return false; 
                }  
            });
        }
        while(!$answer);

        
        if($answer == 'c')
        {
            static::executeCommand($options['symfony-bin-dir'], \Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand::class ,'doctrine:schema:create', [], $options['process-timeout']);
        }
        elseif($answer == 'u')
        {
            static::executeCommand($options['symfony-bin-dir'], \Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand::class ,'doctrine:schema:update', ['--force' => ''], $options['process-timeout']);
        }
        
    }

    protected static function loadFixturies($event, $options)
    {
        
        static::executeCommand($options['symfony-bin-dir'], \Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand::class ,'doctrine:fixtures:load', [], $options['process-timeout']);
        
        
    }

}
