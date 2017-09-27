<?php
namespace Aliso\ApartmentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Parabol\AdminCoreBundle\Entity\Page;


class PageData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */

    private $manager;
  
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        // $this->createPage(array('en' => 'Homepage', 'pl' => 'Strona główna'), null, 'homepage');
        // $this->createPage(array('en' => 'Contact', 'pl' => 'Kontakt'), null, 'contact');
        $this->manager->flush();
    }


    public function createPage($title, $content = null, $type = null)
    {
        $page = new Page();
        foreach($title as $lang => $value)
        {
            $page->translate($lang)->setTitle($value);
        }

        if($content)
        {
            foreach($content as $lang => $value)
            {
                $page->translate($lang)->setContent($value);
            }
        }

        if($type) $page->setType($type);
        

        
        // In order to persist new translations, call mergeNewTranslations method, before flush
        $page->mergeNewTranslations();

        // $repository = $this->manager->getRepository('Gedmo\Translatable\Entity\Translation');

        // $repository
        //     ->translate($page, 'title', 'en', 'EN'.$title)
        //     ->translate($page, 'content', 'en', 'EN'.$title)
        //     ->translate($page, 'title', 'de', 'DE'.$title)
        //     ->translate($page, 'content', 'de', 'DE'.$title)
        //     ;
        
        $this->manager->persist($page);
    }

}