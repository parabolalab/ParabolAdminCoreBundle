<?php

namespace App\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * TextBlock
 *
 * @ORM\Table(name="parabol_text_block")
 * @ORM\Entity(repositoryClass="App\AdminCoreBundle\Repository\TextBlockRepository")
 */
class TextBlock extends \Parabol\AdminCoreBundle\Model\TextBlock
{
	    use 
        	ORMBehaviors\Translatable\Translatable,
        	ORMBehaviors\Sortable\Sortable,
        	\Parabol\FilesUploadBundle\Entity\Base\Files
        ;

      public static function loadValidatorMetadata(ClassMetadata $metadata)
	    {

	        $metadata->addPropertyConstraint('translations', new Assert\Valid());

	    }

}
