<?php

namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 */
abstract class TextBlockTranslation
{

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

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

    /**
     * @var string
     *
     * @ORM\Column(name="buttonUrl", type="string", length=255, nullable=true)
     */
    protected $buttonUrl;

   
    /**
     * Set title
     *
     * @param string $title
     *
     * @return TextBlockTranslation
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
     *
     * @return TextBlockTranslation
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

    /**
     * Set buttonLabel
     *
     * @param string $buttonLabel
     * @return TextBlockTranslation
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

    /**
     * Set buttonUrl
     *
     * @param string $buttonUrl
     * @return TextBlockTranslation
     */
    public function setButtonUrl($buttonUrl)
    {
        $this->buttonUrl = $buttonUrl;

        return $this;
    }

    /**
     * Get buttonUrl
     *
     * @return string 
     */
    public function getButtonUrl()
    {
        return $this->buttonUrl;
    }

   
}
