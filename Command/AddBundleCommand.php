<?php

namespace Parabol\AdminCoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Parabol\AdminCoreBundle\Manipulator\KernelManipulator;
use Symfony\Component\Console\Style\SymfonyStyle;


class AddBundleCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('parabol:add-bundles')
            ->setDescription('Inject bundle to AppKernel class method registerBundles()')
            // ->setHelp('The <info>parabol:admin:register-bundles</info> command fetch bower dependencies (CSS and JS files) to the web root dir, instaling assets and copy content form pre-configuration files.')
            ->setDefinition(array(
                new InputOption('reduceoutput', 'r', InputOption::VALUE_NONE, 'Output will be show only for success messages and will be reduced to single line')
            ))
            ->addArgument(
                'bundles',
                InputArgument::REQUIRED|InputArgument::IS_ARRAY,
                'Bundles'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);
        

        $manip = new KernelManipulator($this->getContainer()->get('kernel'));
        try {
            $manip->addBundles($input->getArgument('bundles'));
            $bundles = (count($input->getArgument('bundles')) > 1 ? "s " : " ") . implode(',', $input->getArgument('bundles'));
            if($input->getOption('reduceoutput') === false) 
                $io->success("Bundle" . $bundles .  " has been registered.");
            else
                $io->writeln("Bundle <info>" . $bundles .  "</info> has been registered.");

        } catch (\RuntimeException $e) {
           if($input->getOption('reduceoutput') === false)  $io->warning($e->getMessage());
        }
        

        
    }

 



}