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

            $container['parameters'][$input->getArgument('name')] = $io->ask($input->getArgument('name'), isset($container['parameters'][$input->getArgument('name')]) ? 
                ( $input->getArgument('type') == 'array' ? implode(',', $container['parameters'][$input->getArgument('name')]) : $container['parameters'][$input->getArgument('name')] ) : $input->getArgument('default'));

            if($input->getArgument('type') == 'array')
            {
                $container['parameters'][$input->getArgument('name')] = explode(',', $container['parameters'][$input->getArgument('name')]);
            }

            file_put_contents($filePath, Yaml::dump($container, 3));

        }



        
        
        
        
    }

 



}