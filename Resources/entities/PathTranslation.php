<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * @ORM\Entity
 * @ORM\Table(name="parabol_path_translation")
 */
class PathTranslation extends \Parabol\AdminCoreBundle\Model\PathTranslation
{
        use ORMBehaviors\Translatable\Translation
        ;
}
