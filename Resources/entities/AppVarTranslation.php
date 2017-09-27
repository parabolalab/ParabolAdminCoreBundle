<?php
namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity()
 * @ORM\Table(name="parabol_app_var_translation")
 */
class AppVarTranslation extends \Parabol\AdminCoreBundle\Model\AppVarTranslation
{
    use ORMBehaviors\Translatable\Translation
        ;
}
