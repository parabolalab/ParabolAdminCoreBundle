<?php

namespace Parabol\AdminCoreBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
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


class InitCommand extends GeneratorCommand
{

    private $bundles = array(    
            //Add
            'Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle()',
            'Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle()',
            'A2lix\TranslationFormBundle\A2lixTranslationFormBundle()',
            'Ivory\CKEditorBundle\IvoryCKEditorBundle()',
            'JMS\I18nRoutingBundle\JMSI18nRoutingBundle()',
            'JMS\TranslationBundle\JMSTranslationBundle()',
            'SimpleThings\FormExtraBundle\SimpleThingsFormExtraBundle()',
            'Liip\ImagineBundle\LiipImagineBundle()',
            'Elao\WebProfilerExtraBundle\WebProfilerExtraBundle()',
            'Liuggio\ExcelBundle\LiuggioExcelBundle()',

            //Generator
            'JMS\AopBundle\JMSAopBundle()',
            'JMS\SecurityExtraBundle\JMSSecurityExtraBundle()',
            'JMS\DiExtraBundle\JMSDiExtraBundle($this)',
            'Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle()',
            'Knp\Bundle\MenuBundle\KnpMenuBundle()',
            'WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle()',
            
            //User
            'FOS\UserBundle\FOSUserBundle()',
            'Parabol\UserBundle\ParabolUserBundle()',

            );

    protected function configure()
    {
        $this
            ->setName('parabol:admin:init')
            ->setDescription('Install all assets & config files (CSS, JS, Bower packages, pre-configuration files)')
            ->setHelp('The <info>parabol:admin:register-bundles</info> command fetch bower dependencies (CSS and JS files) to the web root dir, instaling assets and copy content form pre-configuration files.')
            ->setDefinition(array(
                new InputOption('mode', 'm', InputOption::VALUE_OPTIONAL, 'Mode to fetch dependencies', 'install'),
                new InputOption('bower-bin', 'b', InputOption::VALUE_REQUIRED, 'Path to the bower binary', 'bower')
            ))
        ;
    }

    protected function createGenerator()
    {
        return null;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $formatter = $this->getHelper('formatter');

        $process = new Process(PHP_BINARY . ' app/console parabol:admin:assets-install -m ' . $input->getOption('mode') . ' -b ' . $input->getOption('bower-bin'));
        $process->run();
        echo $process->getOutput();

        die();
        
        // var_dump($input->getArgument('bundle'));
    
        $container = $this->getContainer();
        $projectDir = substr($container->getParameter('kernel.root_dir'),0,-3);

        $kernel = $container->get('kernel');
    	$formatter = $this->getHelperSet()->get('formatter');

        $process = new Process($input->getOption('bower-bin'));
        if($process->run())
        {
            $formattedBlock = $formatter->formatBlock(array('','        <bg=red;fg=white;options=bold>'.$input->getOption('bower-bin').'</> shell command doesn`t exist.        ', ''), 'error');
            
            // $output->writeln("");
            // $output->writeln("<error>                                                   </error>");
            // $output->writeln("<error></error>");
            // $output->writeln("<error>                                                   </error>");
            // $output->writeln("");
            
            return;
        }

        // //Register required bundles
        // $command = $this->getApplication()->find('parabol:admin:register-bundles');
        // $command->run(new ArrayInput(
        //         array(
        //             'command' => 'parabol:admin:register-bundles',
        //             'bundles' => $this->bundles,
        //         )
        //     ), $output);

        
        // //Copy ParabolAdminCoreBundle skeleton files to their destinations
        // $this->copyConfigFiles($kernel, $input, $output);


        

    	
    }

    public function copyConfigFiles(\Symfony\Component\HttpKernel\Kernel $kernel, InputInterface $input, OutputInterface $output)
    {

        $fs = new Filesystem();

        $appDir = $this->getContainer()->getParameter('kernel.root_dir');
        
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../Resources/skeleton');
        foreach ($finder as $file) {
            $fs->copy($file->getRealpath(), $appDir. substr($file->getRelativePathname(),3), true);
        }

        return true;
    }


    public function installDatabase(InputInterface $input, OutputInterface $output)
    {

    }



}