<?php

namespace Parabol\AdminCoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Parabol\BaseBundle\Util\PathUtil;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Parabol\AdminCoreBundle\Manipulator\KernelManipulator;


class InstallBundlesFilesAndAssetsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('parabol:admin:assets-install')
            ->setDescription('Install all assets & config files (CSS, JS, Bower packages, pre-configuration files)')
            ->setHelp('The <info>parabol:admin-install</info> command fetch bower dependencies (CSS and JS files) to the web root dir, instaling assets and copy content form pre-configuration files.')
            ->setDefinition(array(
                new InputOption('bundles', 'bs', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'List of bundles to fetch files and assets', []),
                new InputOption('mode', 'm', InputOption::VALUE_OPTIONAL, 'Mode to fetch dependencies', 'install'),
                new InputOption('bower-bin', 'b', InputOption::VALUE_REQUIRED, 'Path to the bower binary', 'bower')
            ))
            // ->addArgument(
            //     'bundle',
            //     InputArgument::OPTIONAL,
            //     'Bundle'
            // )
            // ->addOption(
            //     'bundle',
            //     null,
            //     InputOption::VALUE_OPTIONAL,
            //     'Bundle name'
            // )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // echo "\n Installing Assets\n";
        $output->writeln("<error>       <bg=red;fg=white;options=bold>Installing Assets</></error>");

        die();
        $container = $this->getContainer();
        $projectDir = substr($container->getParameter('kernel.root_dir'),0,-3);

        $kernel = $container->get('kernel');
    	$formatter = $this->getHelperSet()->get('formatter');

        
        $bundles = $input->getOption('bundles');

        if(!isset($bundles[0]))
        {
            $bundles = array_keys($container->getParameter('kernel.bundles'));
        }


        // foreach($bundles as $name)
        // {
        //     $src = null;
        //     try {
        //         $src = $kernel->locateResource('@'.$name.'/Resources/src');
        //     } catch (\InvalidArgumentException $e) {}
            
        //     if($src)
        //     {
        //         $finder = new Finder();
        //         $finder->files()->in($src);
        //         foreach ($finder as $file) {

        //             switch ($file->getExtension()) {
        //                 case 'yml':

        //                     $source = Yaml::parse($file->getRealpath());
        //                     $target = Yaml::parse($projectDir . $file->getRelativePathname());
        //                     $actions = ['append', 'prepend', 'replace'];
        //                     $preform = false;

        //                     foreach ($actions as $action) {
        //                         if(isset($source[$action]))
        //                         {
        //                             switch ($action) {
        //                                 case 'append':
        //                                     $preform = true;
        //                                     $target = array_merge($target, $source[$action]);
        //                                     break;
        //                                 case 'prepend':
        //                                     $preform = true;
        //                                     $target = array_merge($source[$action], $target);
        //                                     break;
        //                                 case 'replace':
        //                                     $preform = true;
        //                                     $target = $source[$action];
        //                                     break;    
        //                                 default:
                                            
        //                                     break;
        //                             }
        //                         }
        //                     }

        //                     if($preform == true)
        //                     {
        //                         file_put_contents($projectDir . $file->getRelativePathname(), Yaml::dump($target, 6));
        //                     }

        //                     break;
                        
        //                 default:
        //                     # code...
        //                     break;
        //             }
        //             // $path = ($file->getRelativePathname());
        //             // echo "\n";

        //             // 
        //         }
        //     }
        // }

        $commands = [];

        // if ($this->getQuestionHelper()->ask($input, $output, new ConfirmationQuestion($this->getQuestionHelper()->getQuestion('Would you like to create database schema?', 'no', '?'), false))) {
        //     $commands['doctrine:schema:create'] = [];
        // }

        // $commands['doctrine:fixtures:load'] = [
        //         'output-msg' => 'Now you can load fixtures.'
        // ];

    	$commands['assets:install'] = [
    			'--symlink' => true
    	];

    	$commands['admin:assets-install'] = [
    			'--mode' => $input->getOption('mode'),
    			'--bower-bin' => $input->getOption('bower-bin'),
    	];
    	

    	foreach($commands as $command => $arguments)
    	{
    		$command = $this->getApplication()->find($command);
	    	$arguments['command'] = $command;
            $output->writeln("");
            if(isset($arguments['output-msg']))
            {
                $output->writeln($arguments['output-msg']);
                unset($arguments['output-msg']);
            }
	    	$command->run(new ArrayInput($arguments), $output);
    	}


    	
    	
    	

    	foreach($bundles as $name )
    	{
            $bowerFile = null;
    		try {
    			$bowerFile = $kernel->locateResource('@'.$name.'/bower.json');
    		} catch (\InvalidArgumentException $e) {}

    		if($bowerFile)
    		{
                $depth = substr_count(str_replace($projectDir, '', $bowerFile), DIRECTORY_SEPARATOR);
                $path = '';
                for($i=0;$i<$depth;$i++) $path .= '..'.DIRECTORY_SEPARATOR;
                $path .=  $container->getParameter('web_dir') . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'components';  

                $cmd = sprintf(
                    '%s %s --config.directory=%s',
                    $input->getOption('bower-bin'),
                    $input->getOption('mode'),
                    $path
                );

                $process = new Process($cmd);
                $process->setTimeout(300);

    			$process->setWorkingDirectory(dirname($bowerFile));
    			$output->writeln($formatter->formatSection('Bower', sprintf('Fetching %s vendors using the <info>%s</info> mode.', $name, $input->getOption('mode'))));
    			
    			$process->run(function ($type, $buffer) use ($output, $formatter) {
		            if (Process::ERR == $type) {
		                $output->write($formatter->formatBlock($buffer, 'error'));
		            } else {
		                $output->write($formatter->formatSection('Bower', $buffer, 'info' ));
		            }
		        });

    		}

    	}


    	
    }




}