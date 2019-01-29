<?php

namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Parabol\BaseBundle\Entity\Base\BaseEntity;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\MappedSuperclass
 */
abstract class TextBlock extends BaseEntity
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\AdminCoreBundle\Entity\Page", cascade={"persist"})
     * @ORM\JoinColumn(name="page", referencedColumnName="id")
     */
     private $page;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set page
     *
     * @param \App\AdminCoreBundle\Entity\Page $page
     *
     * @return TextBlock
     */
    public function setPage(\App\AdminCoreBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \App\AdminCoreBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    public function addTextBlockTranslation($value)
    {
        $this->addTranslation($value);
         return $this;
    }

    public function getTextBlockTranslations()
    {
        return $this->getTranslations();
    }
}
