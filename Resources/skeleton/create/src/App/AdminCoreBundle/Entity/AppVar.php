<?php

namespace App\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * AppVar
 *
 * @ORM\Table(name="parabol_app_var")
 * @ORM\Entity()
 */
class AppVar extends \Parabol\AdminCoreBundle\Model\AppVar
{
	    use 
        	ORMBehaviors\Translatable\Translatable
        ;
}
