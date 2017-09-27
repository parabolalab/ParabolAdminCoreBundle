<?php

namespace App\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seo
 *
 * @ORM\Table(name="parabol_seo")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\SeoRepository")
 */
class Seo extends \Parabol\AdminCoreBundle\Model\Seo
{
}
