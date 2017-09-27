<?php 

namespace Parabol\AdminCoreBundle\Util;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Parabol\AdminCoreBundle\Entity\AppVar;

class AppVarUtil implements FixtureInterface
{
	protected $manager;

	public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

	public function createAppVar($namespace, $app, $propertyName, $translations, $required = false, $grid = 6, $i18n = false, $readonly = false, $varType = 'string', $cssClass = null)
    {
        $appVar = new AppVar();
        $appVar->setNamespace($namespace);
        $appVar->setCssClass($cssClass);
        $appVar->setVarType($varType);
        $appVar->setApp($app);
        $appVar->setPropertyName($propertyName);
        $appVar->setI18n($i18n);
        $appVar->setGrid($grid);    
        $appVar->setIsRequired($required);    
        $appVar->setIsReadonly((boolean)$readonly);



        foreach($translations as $lang => $values)
        {
             foreach($values as $key => $value)
             {
                    $method = 'set'.ucfirst($key);
                    $appVar->translate($lang)->$method($value); 
             }            
        }

        
        $appVar->mergeNewTranslations();

        $this->manager->persist($appVar);
    }

    public function addAppVar($namespace, $app, $propertyName, $translations, $params = [])
    {

        $params = array_merge(['required' => false, 'grid' => 6, 'i18n' => false, 'readonly' => false, 'varType' => 'string', 'cssClass' => null, 'twigAlias' => null], $params);
        $appVar = new AppVar();
        $appVar->setNamespace($namespace);
        $appVar->setApp($app);
        $appVar->setPropertyName($propertyName);

        foreach($translations as $lang => $values)
        {
             foreach($values as $key => $value)
             {
                    $method = 'set'.ucfirst($key);
                    $appVar->translate($lang)->$method($value); 
             }            
        }

        $appVar->setCssClass($params['cssClass']);
        $appVar->setVarType($params['varType']);
        $appVar->setI18n($params['i18n']);
        $appVar->setGrid($params['grid']);    
        $appVar->setIsRequired($params['required']);    
        $appVar->setIsReadonly((boolean)$params['readonly']);
        $appVar->setTwigAlias($params['twigAlias']); 

        
        $appVar->mergeNewTranslations();

        $this->manager->persist($appVar);
    }
}