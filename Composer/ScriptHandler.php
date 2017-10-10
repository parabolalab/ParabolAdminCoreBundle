<?php

namespace Parabol\AdminCoreBundle\Composer;

use Symfony\Component\Filesystem\Filesystem;;
use Composer\Script\Event;
use Parabol\BaseBundle\Component\Yaml\Yaml;


/**
 * @author Marcin Kalota <marcin@parabolalab.com>
 */
class ScriptHandler extends \Parabol\BaseBundle\Composer\BaseScriptHandler
{

    protected static $bowerfilepaths = [];

    protected static $bundles = [
        'Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle($this)',
        'WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle',
        'Knp\Bundle\MenuBundle\KnpMenuBundle',
        'A2lix\TranslationFormBundle\A2lixTranslationFormBundle',
        'Ivory\CKEditorBundle\IvoryCKEditorBundle',
        'Symfony\Bundle\AsseticBundle\AsseticBundle',
        'Liip\ImagineBundle\LiipImagineBundle'
    ];

    protected static $appParameters = [
        'nodejs' => '/usr/local/bin/node',
        'uglifycss' => '/usr/local/bin/uglifycss',
        'uglifyjs' => '/usr/local/bin/uglifyjs',
        'node_paths' => ['/usr/local/lib/node_modules'],
    ];

    protected static $runnableClasses = [];

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
        static::runExternal($event);

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

           $bundle = preg_replace('#^((.*\/src\/(.*))|(.*\/([^\/\.]+)))\.php$#','$3$5',$path); 

           if(strpos($bundle, '/') === false) $bundle = 'Parabol\\' . strtr($bundle, ['Parabol' => '']) . '\\' . $bundle;
           $bundle = strtr($bundle, ['/' => '\\']) . '()';

           if(strpos($path, '/skeleton/') !== false)
           {
                static::$skeletons[] = preg_replace('#^(.*\/skeleton)\/.*$#','$1', $path);
           }
           else
           {
               if(class_exists($installerClass = preg_replace('/[^\\\\]+$/', '', $bundle).'Composer\\AdminInstaller'))
               {
                    if($installerClass::getBundles())
                    {
                           static::$bundles = array_merge(static::$bundles, (array)$installerClass::getBundles());
                    }

                    if($installerClass::getAppParameters())
                    {
                           static::$appParameters = array_merge(static::$bundles, (array)$installerClass::getAppParameters());
                    }

                    if(method_exists($installerClass, 'run'))
                    {
                        static::$runnableClasses[] = $installerClass;   
                    }
               }
            }

           



           // echo "$bundle\n";
           if(file_exists(dirname($path) . '/bower.json')) static::$bowerfilepaths[] = dirname($path) . '/bower.json';

           static::$bundles[] = $bundle;

        }

        // die();

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
                                    if(file_exists($options['project-dir'] . $path)) $mergedContents[$path] = Yaml::parse(file_get_contents($options['project-dir'] . $path));
                                    else $mergedContents[$path] = [];
                                }

                                $fileContent = Yaml::parse(file_get_contents($info->getPathname()));

                                static::getIO()->writeLn("-> Merging <info>{$path}</info> with <info>{$resourcePath}</info>");
                                if(strpos($info->getPathname(), '/app/config/config.yml') !== false) $fileContent = Yaml::merge(['imports' => [], 'parameters' => []], $fileContent);
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
        if(file_exists($options['project-dir'] . 'bower.json') && $event->getIO()->askConfirmation('Do you want instal bower depedencies from <info>' . $options['project-dir'] . 'bower.json</info>? [y/N] ', false))
        {
            if(file_exists($options['project-dir'] . 'bower.json')) static::executeCommand($options['symfony-bin-dir'], \Parabol\AdminCoreBundle\Command\InstallBowerDepedenciesCommand::class ,'parabol:install-bower-dep', ['list' => [ $options['project-dir'] . 'bower.json' ]], $options['process-timeout']);
        }
    }

    protected static function addParameters($event, $options)
    {
        static::getIO()->comment('Configure additional <info>aplication parameters</info>.');

        static::$appParameters['web_dir'] = isset($options['symfony-web-dir']) ? $options['symfony-web-dir'] : 'web';

        foreach (static::$appParameters as $name => $value) {
            $params = ['name' => $name, 'default' => is_array($value) ? implode(',', $value) : $value, 'type' => is_array($value) ? 'array' : 'string', '--skip-exists' => true];
            if(preg_match('/password|secret/', $name)) $params['--no-dist-value'] = true;
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
        if(static::getIO()->confirm('Do you want load fixtures?', false))
        {
            static::executeCommand($options['symfony-bin-dir'], \Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand::class ,'doctrine:fixtures:load', [], $options['process-timeout']);
            static::getIO()->newLine();
        }
        
        
    }

    protected static function runExternal($event){

        foreach(static::$runnableClasses as $class)
        {
            $class::run($event);
        }

    }

}
