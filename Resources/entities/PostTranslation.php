<?php
namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * @ORM\Entity()
 * @ORM\Table(name="parabol_post_translation")
 */
class PostTranslation extends \Parabol\AdminCoreBundle\Model\PostTranslation
{
        use ORMBehaviors\Translatable\Translation
        ;
}
