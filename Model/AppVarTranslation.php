<?php
namespace Parabol\AdminCoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\MappedSuperclass
 */
abstract class AppVarTranslation
{
    
    /**
     * @ORM\Column(name="namespace_label", type="string", length=50)
     */
    protected $namespaceLabel;

    /**
     * @ORM\Column(name="var_label", type="string", length=50)
     */
    protected $varLabel;

    /**
     * @ORM\Column(name="help", type="text", nullable=true)
     */
    protected $help;

    /**
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    protected $value;

    /**
     * Set namespaceLabel
     *
     * @param string $namespaceLabel
     * @return AppVarTranslation
     */
    public function setNamespaceLabel($namespaceLabel)
    {
        $this->namespaceLabel = $namespaceLabel;

        return $this;
    }

    /**
     * Get namespaceLabel
     *
     * @return string 
     */
    public function getNamespaceLabel()
    {
        return $this->namespaceLabel;
    }

    /**
     * Set varLabel
     *
     * @param string $varLabel
     * @return AppVarTranslation
     */
    public function setVarLabel($varLabel)
    {
        $this->varLabel = $varLabel;

        return $this;
    }

    /**
     * Get varLabel
     *
     * @return string 
     */
    public function getVarLabel()
    {
        return $this->varLabel ? $this->varLabel : ' ';
    }

    /**
     * Set help
     *
     * @param string $help
     * @return AppVarTranslation
     */
    public function setHelp($help)
    {
        $this->help = $help;

        return $this;
    }

    /**
     * Get help
     *
     * @return string 
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return AppVarTranslation
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
