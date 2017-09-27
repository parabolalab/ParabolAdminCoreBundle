<?php
namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * @ORM\Entity()
 * @ORM\Table(name="parabol_page_translation")
 */
class PageTranslation extends \Parabol\AdminCoreBundle\Model\PageTranslation
{
	use 
		ORMBehaviors\Translatable\Translation
	;
}
