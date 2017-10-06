<?php

namespace Parabol\AdminCoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;


class ImageOptimizeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('parabol:image:optimize')
            ->setDescription('Optimaze image with optipng and jpegoptim')
            ->setDefinition(array(
                new InputOption('optipng', 'p', InputOption::VALUE_OPTIONAL, 'Mode to fetch dependencies', '-o7 -preserve -strip all'),
                new InputOption('jpegoptim', 'j', InputOption::VALUE_REQUIRED, 'Path to the bower binary', ' -m75 --strip-all'),
            ))
            ->addArgument(
                'dirs',
                InputArgument::IS_ARRAY,
                'list of directories to search for images',
                ['web/images']
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $extensions = [
            'png' => 'optipng ' . $input->getOption('optipng'), 
            'jpg' => 'jpegoptim '  . $input->getOption('jpegoptim'), 
        ];

        foreach($input->getArgument('dirs') as $dir)
        {
            foreach($extensions as $ext => $executable)
            {

                $cmd = sprintf(
                        'find %s  -type f -iname "*.%s"  -exec %s {} \;',
                        $dir,
                        $ext,
                        $executable
                );
                
                $process = new Process($cmd);
                $process->run(function ($type, $buffer) use ($io) {
                    $io->write($buffer);
                });
            }
        }
        
    }

 



}