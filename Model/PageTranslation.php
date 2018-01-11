<?php
namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 */
abstract class PageTranslation
{
    use 
        ORMBehaviors\Sluggable\Sluggable
        ;

    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $title;

    /*
     * @var string
     *
     * ORM\Column(name="headline", type="string", length=255, nullable=true)
     */
    // protected $headline;

    /*
     * @var string
     *
     * ORM\Column(name="subheadline", type="text", nullable=true)
     */
    // protected $subheadline;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
     * @var string
     *
     * @ORM\Column(name="buttonLabel", type="string", length=255, nullable=true)
     */
    protected $buttonLabel;



    public function getSluggableFields()
    {
        return [ 'title' ];
    }

    /**
     * Set title
     *
     * @param string $title
     * @return PageTranslation
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return PageTranslation
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getContentSections()
    {
        return new \Doctrine\Common\Collections\ArrayCollection(explode('<div style="page-break-after: always"><span style="display: none;">&nbsp;</span></div>', $this->content));
    }

    /**
     * Set headline
     *
     * @param string $headline
     * @return PageTranslation
     */
    // public function setHeadline($headline)
    // {
    //     $this->headline = $headline;

    //     return $this;
    // }

    /**
     * Get headline
     *
     * @return string 
     */
    // public function getHeadline()
    // {
    //     return $this->headline;
    // }

    /**
     * Set subheadline
     *
     * @param string $subheadline
     * @return PageTranslation
     */
    // public function setSubheadline($subheadline)
    // {
    //     $this->subheadline = $subheadline;

    //     return $this;
    // }

    /**
     * Get subheadline
     *
     * @return string 
     */
    // public function getSubheadline()
    // {
    //     return $this->subheadline;
    // }


    /**
     * Set buttonLabel
     *
     * @param string $buttonLabel
     * @return PageTranslation
     */
    public function setButtonLabel($buttonLabel)
    {
        $this->buttonLabel = $buttonLabel;

        return $this;
    }

    /**
     * Get buttonLabel
     *
     * @return string 
     */
    public function getButtonLabel()
    {
        return $this->buttonLabel;
    }

    public function getMenuLabel()
    {
        return $this->buttonLabel ? $this->buttonLabel : $this->title;
    }
}
