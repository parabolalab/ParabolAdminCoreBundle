<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * TextBlock
 *
 * @ORM\Entity()
 * @ORM\Table(name="parabol_text_block_translation")
 * 
 */
class TextBlockTranslation extends \Parabol\AdminCoreBundle\Model\TextBlockTranslation
{
	    use ORMBehaviors\Translatable\Translation
        ;
}
