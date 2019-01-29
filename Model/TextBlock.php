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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;   

    /**
     * @ORM\ManyToOne(targetEntity="App\AdminCoreBundle\Entity\Page", cascade={"persist"})
     * @ORM\JoinColumn(name="page", referencedColumnName="id")
     */
     protected $page;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    protected $type = 'default';


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

    /**
     * Set type
     *
     * @param string $type
     *
     * @return TextBlock
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
