<?php

namespace App\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Parabol\BaseBundle\Validator\Constraints as ParabolAssert;
/**
 * Post
 *
 * @ORM\Table(name="parabol_post")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\PostRepository")
 */
class Post extends \Parabol\AdminCoreBundle\Model\Post
{
	    use 
        	ORMBehaviors\Translatable\Translatable,
       		\Parabol\FilesUploadBundle\Entity\Base\Files
        ;

        
        public static function loadValidatorMetadata(ClassMetadata $metadata)
	    {

	        $metadata->addPropertyConstraint('translations', new Assert\Valid());
	        // $metadata->addPropertyConstraint('files', new ParabolAssert\EntityMinNumber(array('min' => 1)));
	    }

}
