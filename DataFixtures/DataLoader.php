<?php

namespace Parabol\AdminCoreBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\Inflector;


class DataLoader implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */

	private $manager;

    public function load(ObjectManager $manager)
    {

    	$data = $this->getData();

    	if(isset($data))
    	{
    		$this->manager = $manager;

    		foreach($data as $class => $sets)
    		{
    			$this->createObjects($class, $sets);
    		}

    	}
       
    }

    protected function createObjects($class, $sets, $parent = null)
    {
    	foreach($sets as $set)
		{
            echo "Create $class\n";
            $this->createObject(new $class, $set, $parent);
		}	
    }

    protected function createObject($obj, $set, $parent = null)
    {
    	foreach($set as $field => $value)
		{
			if(strpos($field, '\\') !== false)
			{
                $this->manager->persist($obj);
                if(isset($value[0])) $this->createObjects($field, $value, $obj);
				else
                {
                    $this->createObject(new $field, $value, $obj);  
                } 
    		}
            elseif($field == 'Translations')
            {
                $this->manager->persist($obj);
                foreach($value as $locale => $values)
                {
                    $this->createTranslation($obj->translate($locale), $values);
                }
                $obj->mergeNewTranslations();
                
            }
			else
			{
				$method = 'set' . Inflector::classify($field);
				if($value == ':parent') $obj->$method($parent);	
				else $obj->$method(is_object($value) ? $value : trim($value));	
			}
		}
        $this->manager->persist($obj);
        $this->manager->flush($obj);
		
    }

    public function createTranslation($trans, $values)
    {
        foreach($values as $field => $value)
        {
            $method = 'set' . Inflector::classify($field);
            $trans->$method(is_object($value) ? $value : trim($value));
        }
    }


}

