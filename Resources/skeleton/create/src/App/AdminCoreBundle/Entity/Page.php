<?php

namespace App\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Page
 *
 * @ORM\Table(name="parabol_page")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\PageRepository")
 */
class Page extends \Parabol\AdminCoreBundle\Model\Page
{
	    use 
        	ORMBehaviors\Translatable\Translatable,
        	ORMBehaviors\Sortable\Sortable
        	// ,
        	// \Parabol\FilesUploadBundle\Entity\Base\File
        ;

        public static function loadValidatorMetadata(ClassMetadata $metadata)
	    {

	        $metadata->addPropertyConstraint('translations', new Assert\Valid());

	    }

}
