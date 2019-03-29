<?php

namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use A2lix\TranslationFormBundle\Util\Knp\KnpTranslatable;
use Parabol\BaseBundle\Entity\Base\BaseEntity;


use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Parabol\AdminCoreBundle\Validator\Constraints as ParabolAssert;

/**
 * Page
 *
 * @ORM\MappedSuperclass
 */
abstract class Page extends BaseEntity
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
     * @ORM\Column(type="boolean", name="in_menu")
     */
    protected $inMenu = false;

    /**
     * @ORM\Column(type="boolean", name="in_footer")
     */
    protected $inFooter = false;

    /*
     * ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     * ORM\OrderBy({"sort" = "ASC"})
     **/
    // protected $children;

    /*
     * ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     **/
    // protected $parent;


    /**
     * @ORM\Column(name="type", type="string", length=50, nullable=true)
     */
    protected $type;


    /*
     * @var string
     *
     * ORM\Column(name="html_id", type="string", length=50, nullable=true)
     */
    // protected $htmlId;

    /**
     * @ORM\OneToMany(targetEntity="App\AdminCoreBundle\Entity\TextBlock", mappedBy="page", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $textBlocks;

    
    public function __construct() {
        // $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->textBlocks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // public static function loadValidatorMetadata(ClassMetadata $metadata)
    // {
    //     $metadata->addPropertyConstraint('translations', new Assert\Valid());

    //     $metadata->addPropertyConstraint('files', new ParabolAssert\EntityMinNumber(array('min' => 0)));


    // }
    
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
     * @return Page
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
     * Set inMenu
     *
     * @param boolean $inMenu
     * @return Page
     */
    public function setInMenu($inMenu)
    {
        $this->inMenu = $inMenu;

        return $this;
    }

    /**
     * Get inMenu
     *
     * @return boolean 
     */
    public function getInMenu()
    {
        return $this->inMenu;
    }

    /**
     * Set inFooter
     *
     * @param boolean $inFooter
     * @return Page
     */
    public function setInFooter($inFooter)
    {
        $this->inFooter = $inFooter;

        return $this;
    }

    /**
     * Get inFooter
     *
     * @return boolean 
     */
    public function getInFooter()
    {
        return $this->inFooter;
    }

    // /**
    //  * Set parent
    //  *
    //  * @param \stdClass $parent
    //  * @return Page
    //  */
    // public function setParent($parent)
    // {
    //     $this->parent = $parent;

    //     return $this;
    // }

    // /**
    //  * Get parent
    //  *
    //  * @return \stdClass 
    //  */
    // public function getParent()
    // {
    //     return $this->parent;
    // }

    // /**
    //  * Add children
    //  *
    //  * @param \Parabol\AdminCoreBundle\Entity\Page $children
    //  * @return Page
    //  */
    // public function addChild(\Parabol\AdminCoreBundle\Entity\Page $children)
    // {
    //     $this->children[] = $children;

    //     return $this;
    // }

    // /**
    //  * Remove children
    //  *
    //  * @param \Parabol\AdminCoreBundle\Entity\Page $children
    //  */
    // public function removeChild(\Parabol\AdminCoreBundle\Entity\Page $children)
    // {
    //     $this->children->removeElement($children);
    // }

    // /**
    //  * Get children
    //  *
    //  * @return \Doctrine\Common\Collections\Collection 
    //  */
    // public function getChildren()
    // {
    //     return $this->children;
    // }

    /**
     * Set type
     *
     * @param string $type
     * @return Page
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
    * Add textBlock
    *
    * @param \App\AdminCoreBundle\Entity\TextBlock $textBlock
    *
    * @return Page
    */
    public function addTextBlock(\App\AdminCoreBundle\Entity\TextBlock $textBlock)
    {
        $textBlock->setPage($this);
        $this->textBlocks[] = $textBlock;

        return $this;
    }

    /**
    * Remove textBlock
    *
    * @param \App\AdminCoreBundle\Entity\TextBlock $textBlock
    */
    public function removeTextBlock(\App\AdminCoreBundle\Entity\TextBlock $textBlock)
    {
        $this->textBlocks->removeElement($textBlock);
    }

    /**
    * Get textBlocks
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getTextBlocks()
    {
        return $this->textBlocks;
    }


    /**
     * Set website
     *
     * @param string $website
     * @return Page
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }    

    public static function default_type()
    {
        return array('homepage', 'contact', 'rightsidebar');
    }

    public static function undeletableTypes()
    {
        return array('homepage', 'contact');
    }


    // /**
    //  * Set htmlId
    //  *
    //  * @param string $htmlId
    //  * @return Page
    //  */
    // public function setHtmlId($htmlId)
    // {
    //     $this->htmlId = $htmlId;

    //     return $this;
    // }

    // /**
    //  * Get htmlId
    //  *
    //  * @return string 
    //  */
    // public function getHtmlId()
    // {
    //     return $this->htmlId;
    // }

    public function getCols()
    {
        return explode('<div style="page-break-after: always"><span style="display: none;">&nbsp;</span></div>', $this->getContent());
    }

}
