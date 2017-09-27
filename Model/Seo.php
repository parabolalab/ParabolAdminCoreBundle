<?php
namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Parabol\BaseBundle\Entity\Base\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity(fields={"url", "inherited"}, errorPath="url")
 */
abstract class Seo extends BaseEntity 
{

    public static $valueMethods = array(
        'title' => 'getTitle',
        'description' => 'getDescription',
        'keywords' => 'getKeywords',
        'og:title' => 'getOgTitle',
        'og:description' => 'getOgDescription',
        'og:image' => 'getOgImage',
        'robots' => 'getRobotsAsString',
    );

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;  

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="inherited", type="boolean")
     */
    protected $inherited = true;
    
    /**
     * @var string
     *
     * @ORM\Column(name="redirect_to", type="string", length=255, nullable=true)
     */
    protected $redirectTo;

    /**
     * @var integer
     *
     * @ORM\Column(name="redirect_status_code", type="smallint", nullable=true)
     */
    protected $redirectStatusCode;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", nullable=true)
     */
    protected $keywords;

    /**
    * @var string $ogTitle
    *
    * @ORM\Column(name="og_title", type="string", length=255, nullable=true)
    */
    protected $ogTitle;

    /**
    * @var string $ogDescription
    *
    * @ORM\Column(name="og_description", type="text", nullable=true)
    */
    protected $ogDescription;
    
    /**
    * @var string $ogImage
    *
    * @ORM\Column(name="og_image", type="string", length=255, nullable=true)
    */
    protected $ogImage;   

    /**
    * @var string $ogImage
    *
    * @ORM\Column(name="robots", type="array", nullable=true)
    */
    protected $robots;
   

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
     * Set url
     *
     * @param string $url
     * @return Seo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Seo
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
     * Set description
     *
     * @param string $description
     * @return Seo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Seo
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set ogTitle
     *
     * @param string $ogTitle
     * @return Seo
     */
    public function setOgTitle($ogTitle)
    {
        $this->ogTitle = $ogTitle;

        return $this;
    }

    /**
     * Get ogTitle
     *
     * @return string 
     */
    public function getOgTitle()
    {
        return $this->ogTitle;
    }

    /**
     * Set ogDescription
     *
     * @param string $ogDescription
     * @return Seo
     */
    public function setOgDescription($ogDescription)
    {
        $this->ogDescription = $ogDescription;

        return $this;
    }

    /**
     * Get ogDescription
     *
     * @return string 
     */
    public function getOgDescription()
    {
        return $this->ogDescription;
    }

    /**
     * Set ogImage
     *
     * @param string $ogImage
     * @return Seo
     */
    public function setOgImage($ogImage)
    {
        $this->ogImage = $ogImage;

        return $this;
    }

    /**
     * Get ogImage
     *
     * @return string 
     */
    public function getOgImage()
    {
        return $this->ogImage;
    }

 
    /**
     * Set inherited
     *
     * @param boolean $inherited
     * @return Seo
     */
    public function setInherited($inherited)
    {
        $this->inherited = $inherited;

        return $this;
    }

    /**
     * Get inherited
     *
     * @return boolean 
     */
    public function getInherited()
    {
        return $this->inherited;
    }

    /**
     * Set redirectTo
     *
     * @param string $redirectTo
     * @return Seo
     */
    public function setRedirectTo($redirectTo)
    {
        $this->redirectTo = $redirectTo;

        return $this;
    }

    /**
     * Get redirectTo
     *
     * @return string 
     */
    public function getRedirectTo()
    {
        return $this->redirectTo;
    }

    /**
     * Set redirectStatusCode
     *
     * @param integer $redirectStatusCode
     * @return Seo
     */
    public function setRedirectStatusCode($redirectStatusCode)
    {
        $this->redirectStatusCode = $redirectStatusCode;

        return $this;
    }

    /**
     * Get redirectStatusCode
     *
     * @return integer 
     */
    public function getRedirectStatusCode()
    {
        return $this->redirectStatusCode;
    }

    /**
     * Set robots
     *
     * @param array $robots
     * @return Seo
     */
    public function setRobots($robots)
    {
        $this->robots = $robots;

        return $this;
    }

    /**
     * Get robots
     *
     * @return array 
     */
    public function getRobots()
    {
        return $this->robots;
    }

}
