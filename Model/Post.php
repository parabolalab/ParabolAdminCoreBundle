<?php

namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use A2lix\TranslationFormBundle\Util\Knp\KnpTranslatable;
use Parabol\BaseBundle\Entity\Base\BaseEntity;


use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post
 *
 * @ORM\MappedSuperclass
 */
abstract class Post extends BaseEntity
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
     * @ORM\Column(type="boolean", name="is_enabled")
     */
    protected $isEnabled = true;

    /**
     * @ORM\Column(name="type", type="string", length=20)
     */
    protected $type = 'news';

    /**
     * @ORM\Column(name="display_from", type="datetime", nullable=true)
     */
    protected $displayFrom;

    /**
     * @ORM\Column(name="display_to", type="datetime", nullable=true)
     */
    protected $displayTo;

 
    public function __construct() {
        $this->displayFrom = new \DateTime(); 
    }

    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }    

   


    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     * @return Post
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean 
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Post
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

   

    /**
     * Set displayFrom
     *
     * @param \DateTime $displayFrom
     * @return Post
     */
    public function setDisplayFrom($displayFrom)
    {
        $this->displayFrom = $displayFrom;

        return $this;
    }

    /**
     * Get displayFrom
     *
     * @return \DateTime 
     */
    public function getDisplayFrom()
    {
        return $this->displayFrom;
    }

    /**
     * Set displayTo
     *
     * @param \DateTime $displayTo
     * @return Post
     */
    public function setDisplayTo($displayTo)
    {
        $this->displayTo = $displayTo;

        return $this;
    }

    /**
     * Get displayTo
     *
     * @return \DateTime 
     */
    public function getDisplayTo()
    {
        return $this->displayTo;
    }


    public function getParts()
    {
        return explode('<div style="page-break-after: always"><span style="display: none;">&nbsp;</span></div>', $this->getContent());
    }
}
