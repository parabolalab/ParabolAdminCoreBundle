<?php
namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\MappedSuperclass
 */
abstract class PostTranslation
{
    use ORMBehaviors\Sluggable\Sluggable
        ;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="lead", type="string", length=500)
     */
    protected $lead;
    
    /*
     * @var string
     *
     * ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;


    public function getSluggableFields()
    {
        return [ 'title' ];
    }

    /**
     * Set title
     *
     * @param string $title
     * @return PostTranslation
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
     * @return PostTranslation
     */
    // public function setContent($content)
    // {
    //     $this->content = $content;

    //     return $this;
    // }

    /**
     * Get content
     *
     * @return string 
     */
    // public function getContent()
    // {
    //     return $this->content;
    // }

    /**
     * Set lead
     *
     * @param string $lead
     * @return PostTranslation
     */
    public function setLead($lead)
    {
        $this->lead = $lead;

        return $this;
    }

    /**
     * Get lead
     *
     * @return string 
     */
    public function getLead()
    {
        return $this->lead;
    }
}
