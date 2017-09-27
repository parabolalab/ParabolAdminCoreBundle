<?php
namespace Aliso\ApartmentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Parabol\AdminCoreBundle\Entity\TextBlock;


class TextBlockData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */

    private $manager;
  
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        // $this->createPage('Homepage');
        // $this->createPage('Contact');
        // $this->manager->flush();
    }


    public function createPage($title)
    {
    //     $page = new Page();
    //     $page->translate('pl')->setTitle('PL ' . $title . '');
    //     $page->translate('pl')->setContent('PL ' . $title . '');
    //     $page->translate('en')->setTitle('EN ' . $title . '');
    //     $page->translate('en')->setContent('EN ' . $title . '');
        

        
    // // In order to persist new translations, call mergeNewTranslations method, before flush
    // $page->mergeNewTranslations();

    //     // $repository = $this->manager->getRepository('Gedmo\Translatable\Entity\Translation');

    //     // $repository
    //     //     ->translate($page, 'title', 'en', 'EN'.$title)
    //     //     ->translate($page, 'content', 'en', 'EN'.$title)
    //     //     ->translate($page, 'title', 'de', 'DE'.$title)
    //     //     ->translate($page, 'content', 'de', 'DE'.$title)
    //     //     ;
        
    //     $this->manager->persist($page);
    }

}