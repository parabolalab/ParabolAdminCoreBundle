<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * Page
 *
 * @ORM\Table(name="parabol_page")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\PageRepository")
 */
class Page extends \Parabol\AdminCoreBundle\Model\Page
{
	use
		ORMBehaviors\Translatable\Translatable
	;

	/*
     * @ORM\ManyToMany(targetEntity="\Parabol\LocaleBundle\Entity\Country")     
     * @ORM\JoinTable(name="page_countries",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    // private $countries;

    // public function __construct() {
    // 	parent::__construct();
    //     $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    // }

    // /**
    //  * Add country
    //  *
    //  * @param \Parabol\LocaleBundle\Entity\Country $country
    //  *
    //  * @return Product
    //  */
    // public function addCountry(\Parabol\LocaleBundle\Entity\Country $country)
    // {
    //     $this->countries[] = $country;

    //     return $this;
    // }

    // /**
    //  * Remove country
    //  *
    //  * @param \Parabol\LocaleBundle\Entity\Country $country
    //  */
    // public function removeCountry(\Parabol\LocaleBundle\Entity\Country $country)
    // {
    //     $this->countries->removeElement($country);
    // }

    // /**
    //  * Get countries
    //  *
    //  * @return \Doctrine\Common\Collections\Collection
    //  */
    // public function getCountries()
    // {
    //     return $this->countries;
    // }
}
