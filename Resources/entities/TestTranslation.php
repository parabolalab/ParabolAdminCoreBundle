<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * TestTranslation
 *
 * @ORM\Table(name="parabol_test_translation")
 * @ORM\Entity
 */
class TestTranslation extends \Parabol\AdminCoreBundle\Model\TestTranslation
{
        use ORMBehaviors\Translatable\Translation
        ;
}
