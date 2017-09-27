<?php

namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use A2lix\TranslationFormBundle\Util\Knp\KnpTranslatable;
use Parabol\BaseBundle\Entity\Base\BaseEntity;

/**
 * AppVar
 *
 * @ORM\MappedSuperclass
 */
abstract class AppVar extends BaseEntity
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
     * @ORM\Column(type="boolean", name="i18n")
     */
    protected $i18n = true;

    /**
     * @ORM\Column(name="app", type="string", length=20)
     */
    protected $app = 'all'; //all, app, admin, api etc.
    
    /**
     * @ORM\Column(name="namespace", type="string", length=50)
     */
    protected $namespace;

    /**
     * @ORM\Column(name="property_name", type="string", length=150)
     */
    protected $propertyName; 

    /**
     * @ORM\Column(name="var_type", type="string", length=255)
     */
    protected $varType = 'string'; //string, text, boolean, integer, decimal , file

    /**
     * @ORM\Column(name="file_path", type="string", length=255, nullable=true)
     */
    protected $filePath = null;

    /**
     * @ORM\Column(type="boolean", name="is_required")
     */
    protected $isRequired = false;

    /**
     * @ORM\Column(type="boolean", name="is_enabled")
     */
    protected $isEnabled = true;

    /**
     * @ORM\Column(type="boolean", name="is_readonly")
     */
    protected $isReadonly = false;

    /**
     * @ORM\Column(type="smallint", name="grid")
     */
    protected $grid = 6;

    /**
     * @ORM\Column(name="css", type="string", length=255, nullable=true)
     */
    protected $css = null; 

    /**
     * @ORM\Column(name="css_class", type="string", length=255, nullable=true)
     */
    protected $cssClass = null; 

    /**
     * @ORM\Column(name="twig_alias", type="string", length=255, nullable=true)
     */
    protected $twigAlias = null; 

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
     * Set i18n
     *
     * @param boolean $i18n
     * @return AppVar
     */
    public function setI18n($i18n)
    {
        $this->i18n = $i18n;

        return $this;
    }

    /**
     * Get i18n
     *
     * @return boolean 
     */
    public function getI18n()
    {
        return $this->i18n;
    }

    /**
     * Set app
     *
     * @param string $app
     * @return AppVar
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Get app
     *
     * @return string 
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Set namespace
     *
     * @param string $namespace
     * @return AppVar
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get namespace
     *
     * @return string 
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set propertyName
     *
     * @param string $propertyName
     * @return AppVar
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    /**
     * Get propertyName
     *
     * @return string 
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * Set varType
     *
     * @param string $varType
     * @return AppVar
     */
    public function setVarType($varType)
    {
        $this->varType = $varType;

        if($this->isCollection()) $this->setCssClass( trim($this->getCssClass() . ' col-' . (count($this->getVarType()) + 1)));

        return $this;
    }

    /**
     * Get varType
     *
     * @return string 
     */
    public function getVarType()
    {
        $data = json_decode($this->varType, true);
        return is_array($data) ? $data : $this->varType;
    }

    public function isFile()
    {
        return $this->getFormType() == 'Symfony\Component\Form\Extension\Core\Type\FileType';
    }

     public function isCollection()
    {
        return $this->getFormType() == 'Symfony\Component\Form\Extension\Core\Type\CollectionType';
    }

    /**
     * Set filePath
     *
     * @param string $filePath
     * @return AppVar
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string 
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set isRequired
     *
     * @param boolean $isRequired
     * @return AppVar
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    /**
     * Get isRequired
     *
     * @return boolean 
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     * @return AppVar
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
     * Set isReadonly
     *
     * @param boolean $isReadonly
     * @return AppVar
     */
    public function setIsReadonly($isReadonly)
    {
        $this->isReadonly = $isReadonly;

        return $this;
    }

    /**
     * Get isReadonly
     *
     * @return boolean 
     */
    public function getIsReadonly()
    {
        return $this->isReadonly;
    }

    /**
     * Set grid
     *
     * @param integer $grid
     * @return AppVar
     */
    public function setGrid($grid)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get grid
     *
     * @return integer 
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * Set css
     *
     * @param string $css
     * @return AppVar
     */
    public function setCss($css)
    {
        $this->css = $css;

        return $this;
    }

    /**
     * Get css
     *
     * @return string 
     */
    public function getCss()
    {
        if(!$this->css)
        {
            switch($this->getVarType())
            {
                case 'longtext':
                    return "height: 300px;";

            }

            return null;
        }
        else
        {
            return $this->css;
        }
        
    }


     /**
     * Set cssClass
     *
     * @param string $cssClass
     * @return AppVar
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
        return $this;
    }

    /**
     * Get cssClass
     *
     * @return string 
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * Set twigAlias
     *
     * @param string $twigAlias
     * @return AppVar
     */
    public function setTwigAlias($twigAlias)
    {
        $this->twigAlias = $twigAlias;
        return $this;
    }

    /**
     * Get twigAlias
     *
     * @return string 
     */
    public function getTwigAlias()
    {
        return $this->twigAlias;
    }

    public function getFormType($raw = false)
    {
        return self::formType($this->getVarType(), $this->getI18n(), $raw);        
    }

    public static function formType($type, $i18n = false, $raw = false)
    {
        // if(!$raw && $i18n) $type = 'A2lix\TranslationFormBundle\Form\Type\TranslationsType';
        // else
            switch($type)
            {
                case 'ckeditor':
                    $type = 'Ivory\CKEditorBundle\Form\Type\CKEditorType';
                    break;
                case 'text':
                case 'longtext':
                    $type = 'Symfony\Component\Form\Extension\Core\Type\TextareaType';
                    break;
                case 'file':
                    $type = 'Symfony\Component\Form\Extension\Core\Type\FileType';
                    break;
                default:
                    if(is_array($type)) $type = 'Symfony\Component\Form\Extension\Core\Type\CollectionType';
                    else $type = 'Symfony\Component\Form\Extension\Core\Type\TextType';

            }
        return $type;
    }

    public function getValidators()
    {

        if($this->isCollection())
        {
            $validators = array();
            foreach($this->getVarType() as $name => $type)
            {
                $validators[$name] = $this->validatorsGuesser($type);
            }
            
        }
        else
        {
            $validators = $this->validatorsGuesser($this->getVarType());
        }

        return $validators;

        
    }

    private function validatorsGuesser($type)
    {
        $validators = array();
        switch($type)
        {
            case 'decimal':
                $validators[] = new \Symfony\Component\Validator\Constraints\Type(array('type' => 'numeric'));
            break;

            case 'udecimal_or_upercent':
                $validators[] = new \Symfony\Component\Validator\Constraints\Regex(array('pattern' => '/^[\d]+\.?\d{0,2}%?$/', 'message' => 'Wartość musi być nieujemna numeryczna lub nieujemna procentowa'));
            break;
        }

        if($this->getIsRequired()) $validators[] = new \Symfony\Component\Validator\Constraints\NotBlank();

        return $validators;
    }

}
