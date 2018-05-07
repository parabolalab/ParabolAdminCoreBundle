<?php
namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Parabol\BaseBundle\Entity\Base\BaseEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity(fields={"url", "applyToAll"}, errorPath="url")
 */
abstract class Code extends BaseEntity 
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="applyToAll", type="boolean")
     */
    protected $applyToAll = false;


    /**
     * @var string
     *
     * @ORM\Column(name="inherited", type="boolean")
     */
    protected $inherited = false;
    

    /**
     * @var string
     *
     * @ORM\Column(name="head", type="text", nullable=true)
     */
    protected $head;

    /**
     * @var string
     *
     * @ORM\Column(name="hasHead", type="boolean")
     */
    protected $hasHead = false;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    protected $body;


     /**
     * @var string
     *
     * @ORM\Column(name="hasBody", type="boolean")
     */
    protected $hasBody = false;

   
   

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
     * Set applyToAll
     *
     * @param boolean $applyToAll
     * @return Seo
     */
    public function setApplyToAll($applyToAll)
    {
        $this->applyToAll = $applyToAll;

        return $this;
    }

    /**
     * Get applyToAll
     *
     * @return boolean 
     */
    public function getApplyToAll()
    {
        return $this->applyToAll;
    }

    /**
     * Set inherited
     *
     * @param string $inherited
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
     * @return string 
     */
    public function getInherited()
    {
        return $this->inherited;
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
     * Set head
     *
     * @param string $head
     * @return Seo
     */
    public function setHead($head)
    {
        $this->head = trim($head);
        $this->setHasHead((boolean)$this->head);
        return $this;
    }

    /**
     * Get head
     *
     * @return string 
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Set hasHead
     *
     * @param string $hasHead
     * @return Seo
     */
    private function setHasHead($hasHead)
    {
        $this->hasHead = $hasHead;

        return $this;
    }

    /**
     * Get hasHead
     *
     * @return string 
     */
    public function getHasHead()
    {
        return $this->hasHead;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Seo
     */
    public function setBody($body)
    {
        $this->body = trim($body);
        $this->setHasBody((boolean)$this->head);
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }


    /**
     * Set hasBody
     *
     * @param string $hasBody
     * @return Seo
     */
    private function setHasBody($hasBody)
    {
        $this->hasBody = $hasBody;

        return $this;
    }

    /**
     * Get hasBody
     *
     * @return string 
     */
    public function getHasBody()
    {
        return $this->hasBody;
    }

   
}
