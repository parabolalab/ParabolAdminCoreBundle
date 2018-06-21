<?php
namespace Parabol\AdminCoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\AdminCoreBundle\Entity\Page;


class PageData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */

    protected $manager;
  
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        // $this->createPage(array('en' => 'Homepage', 'pl' => 'Strona główna'), array('en' => 'Homepage', 'pl' => 'Strona główna'), null, 'homepage');
        // $this->createPage(array('en' => 'Contact', 'pl' => 'Kontakt'), array('en' => 'Contact', 'pl' => 'Kontakt'), null, 'contact');
        $this->manager->flush();
    }


    public function createPage($name, $title, $content = null, $type = null)
    {
        $page = new Page();
        foreach($name as $lang => $value)
        {
            $page->translate($lang)->setName($value);
        }

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

        
        $this->manager->persist($page);

        return $page;
    }

}