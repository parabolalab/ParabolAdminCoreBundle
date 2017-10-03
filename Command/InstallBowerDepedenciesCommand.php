<?php

namespace Parabol\AdminCoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Style\SymfonyStyle;


class InstallBowerDepedenciesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('parabol:install-bower-dep')
            ->setDescription('Install bower depedencies')
            ->setHelp('The <info>parabol:install-bower-dep</info> command fetch bower dependencies from the list with bundles names or directly paths to bower.json files.')
            ->setDefinition(array(
                new InputOption('mode', 'm', InputOption::VALUE_REQUIRED, 'Mode to fetch dependencies', 'install'),
                new InputOption('bower-bin', 'bb', InputOption::VALUE_REQUIRED, 'Path to the bower binary', 'bower'),
                new InputOption('web-dir', 'wd', InputOption::VALUE_REQUIRED, 'Path to the bower binary', 'web')
            ))
            ->addArgument('list', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'List of bundles or path to bower.json files')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);

        $container = $this->getContainer();
        $projectDir = substr($container->getParameter('kernel.root_dir'),0,-3);

        $kernel = $container->get('kernel');
        $list = $input->getArgument('list');
        
        foreach($list as $name)
        {
           
            $bowerFile = null;

            if(strpos($name, '/bower.json') !== false)
            {
                if(file_exists($name)) $bowerFile = $name;
            }
            else
            {
                try {
                    $bowerFile = $kernel->locateResource('@'.$name.'/bower.json');
                } catch (\InvalidArgumentException $e) {

                }
            }


            if($bowerFile)
            {
                
                $io->comment(sprintf('Installing bower dependencies for the <info>%s</info>', $name));

                $cmd = sprintf(
                    '%s %s --config.directory=%s',
                    $input->getOption('bower-bin'),
                    $input->getOption('mode'),
                    $projectDir . $input->getOption('web-dir') . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'components'
                );

                $process = new Process($cmd);
                $process->setTimeout(300);

                $process->setWorkingDirectory(dirname($bowerFile));

                $process->run(function ($type, $buffer) use ($io) {
                        if (Process::ERR == $type) {
                            $io->write(preg_replace('/^(bower )([^#]+)([^\d]*)([^ ]+)([\s]{2,})([^ ]+)/m', '$1<error>$2$3$4</error>$5<fg=red>$6</>' ,$buffer));
                        } else {

                            if(strpos($buffer, 'bower') === 0)
                            {
                                $io->write(preg_replace('/^(bower )([^#]+)([^\d]*)([^ ]+)([\s]{2,})([^ ]+)/m', '$1<info>$2$3$4</info>$5<fg=cyan>$6</>' ,$buffer));
                            }
                        }
                  });

            }

        }


        
    }




}