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

    protected static $options = array(
        
    );

    protected static function getOptions(Event $event)
    {
        $options = array_merge(static::$options, $event->getComposer()->getPackage()->getExtra());

        $options['vendor-dir'] = $event->getComposer()->getConfig()->get('vendor-dir');

        

        return $options;
    }


    /**
     * Asks if the new directory structure should be used, installs the structure if needed.
     *
     * @param Event $event
     */
    public static function installBundles(Event $event)
    {

        $options = static::getOptions($event);

        $fs = new Filesystem();

        $currentDir = dirname(__FILE__);

        $kernelManipulator = new KernelManipulator(new \AppKernel('dev', true));
        $configSkeletons = [];

        $bundles = [
            'Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(this)',
            'WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle()',
            'Knp\Bundle\MenuBundle\KnpMenuBundle()',
            'A2lix\TranslationFormBundle\A2lixTranslationFormBundle()',
            'Ivory\CKEditorBundle\IvoryCKEditorBundle()'
        ];

        $kernelManipulator->addBundle($bundle);
        

        foreach(glob('{' . $currentDir . '/../../*/*Bundle.php,' . $currentDir . '/../../*/Resources/skeleton/src/*/*/*Bundle.php' . '}', GLOB_BRACE) as $path)
        {
           if(strpos($path, '/skeleton/') !== false)
           {
                $configSkeleton = (preg_replace('#^(.*\/skeleton\/).*$#','$1',$path) . $options['symfony-app-dir'] . '/config/config.yml');
                var_dump($configSkeleton);
                var_dump(file_exists($configSkeleton));
                if(file_exists($configSkeleton)) $configSkeletons[] = $configSkeleton;

           }

           $bundle = preg_replace('#^((.*\/src\/(.*))|(.*\/([^\/\.]+)))\.php$#','$3$5',$path) . '()'; 
           if(strpos($bundle, '/') === false) $bundle = 'Parabol\\' . strtr($bundle, ['Parabol' => '']) . '\\' . $bundle;
           else $bundle = strtr($bundle, ['/' => '\\']);

           try
           {
                $kernelManipulator->addBundle($bundle);
           }
           catch(\RuntimeException $e)
           {
                if(strpos($e->getMessage(), 'is already defined') === false)
                {
                    throw new \RuntimeException($e->getMessage(), $e->getCode());
                    
                }
           }
        }


        $fs->mirror($currentDir . '/../Resources/skeleton/', $currentDir . '/../../../../');

        // echo 'dir: ' . getcwd() . "\n";


        if(isset($configSkeletons[0]))
        {
            $configPath = $options['symfony-app-dir'] . '/config/config.yml';
            $config = Yaml::parse(file_get_contents($configPath));

            foreach($configSkeletons as $configSkeleton)
            {
                $newconfig = Yaml::parse(file_get_contents($configSkeleton));
                $config = array_merge_recursive($config, $newconfig);
            }

            $dumped = Yaml::dump($config, 6);
            $dumped = preg_replace('/^([^\s#]+)/m',  "\n$1", $dumped);

            $fs->copy($configPath, $configPath .'.org.yml');
            $fs->dumpFile($options['symfony-app-dir'] . '/config/config_merged.yml', trim($dumped));
        }




    }

}
