<?php
namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * @ORM\Entity()
 * @ORM\Table(name="parabol_gallery_translation")
 */
class GalleryTranslation extends \Parabol\AdminCoreBundle\Model\GalleryTranslation
{
       use ORMBehaviors\Translatable\Translation
        ;
}
