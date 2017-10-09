<?php

namespace Parabol\AdminCoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class AddParameterCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('parabol:add-parameter')
            ->setDescription('add parameter to parameters.yaml')
            // ->setHelp('The <info>parabol:admin:register-bundles</info> command fetch bower dependencies (CSS and JS files) to the web root dir, instaling assets and copy content form pre-configuration files.')
            ->setDefinition(array(
                new InputOption('no-dist-value', 'nd', InputOption::VALUE_NONE, 'Parameter will be added to parameters.yml.dist without value, useful for password parameters'),
                new InputOption('multiple-subparams', 'm', InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY, ''),
                new InputOption('skip-exists', 's', InputOption::VALUE_NONE, ''),

            ))
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Parameter name'
            )
            ->addArgument(
                'default',
                InputArgument::OPTIONAL,
                'Parameter default value'
            )
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'Parameter value type'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);





        $filePath = $this->getContainer()->get('kernel')->getRootDir() . '/config/parameters.yml';

        if(file_exists($filePath))
        {
            $container = Yaml::parse(file_get_contents($filePath));
            $parametersDist = file_get_contents($filePath . '.dist');

            if(!$input->getOption('skip-exists') || !isset($container['parameters'][$input->getArgument('name')]))
            {

                if(count($input->getOption('multiple-subparams')) === 0)
                {
                    $container['parameters'][$input->getArgument('name')] = $io->ask($input->getArgument('name'), isset($container['parameters'][$input->getArgument('name')]) ? 
                        ( $input->getArgument('type') == 'array' ? implode(',', $container['parameters'][$input->getArgument('name')]) : $container['parameters'][$input->getArgument('name')] ) : $input->getArgument('default'));

                    if($input->getArgument('type') == 'array')
                    {
                        $container['parameters'][$input->getArgument('name')] = explode(',', $container['parameters'][$input->getArgument('name')]);
                    }
                }
                else
                {
                    
                    $container['parameters'][$input->getArgument('name')] = [];
                    $set = 0;
                    do
                    {
                        foreach ($input->getOption('multiple-subparams') as $i => $value) {
                            [$name, $default, $type] = explode(':', $value . '::');
                            
                            if($set) $default = null;

                            if(isset($container['parameters'][$input->getArgument('name')][$set][$name]))
                            {
                                $default = $container['parameters'][$input->getArgument('name')][$set][$name];
                            }

                            $container['parameters'][$input->getArgument('name')][$set][$name] = 
                                $type === 'hidden' ? $io->askHidden($name . ($default ? ' <fg=white>[</><fg=yellow>' . $default . '</><fg=white>]</>' : ''), function($value) use ($default) { if(!$value) $value = $default; return $value; })
                                : $io->ask($name, $default ? $default : null )
                            ;
                        }
                        $set++;
                    }
                    while($io->confirm('Do you want add next?'));
                }


                if(!preg_match('/^\s+' . $input->getArgument('name')  . ':/m', $parametersDist))
                {
                    $parametersDist .=  PHP_EOL . '    ' . $input->getArgument('name') . ':';

                    if(!$input->getOption('no-dist-value'))
                    {
                        // if(is_array($container['parameters'][$input->getArgument('name')]))
                        // {
                        //     $parametersDist .= PHP_EOL . '        - ' . implode(PHP_EOL . '        - ', $container['parameters'][$input->getArgument('name')]);
                        // }
                        // else
                        // {
                        //     $parametersDist .= ' ' . $container['parameters'][$input->getArgument('name')];
                        // }
                    }

                    file_put_contents($filePath . '.dist', $parametersDist);
                }

                file_put_contents($filePath, Yaml::dump($container, 3));
            }

        }



        
        
        
        
    }

 



}