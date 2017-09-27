<?php
namespace App\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity()
 * @ORM\Table(name="parabol_post_translation")
 */
class PostTranslation extends \Parabol\AdminCoreBundle\Model\PostTranslation
{
    use ORMBehaviors\Translatable\Translation
        ;

    /**
     * @var string
     *
     * @ORM\Column(name="lead", type="string", length=500)
     * @Assert\NotBlank()
     */
    protected $lead;
}
