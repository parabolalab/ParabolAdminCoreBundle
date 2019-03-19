<?php

namespace App\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Seo
 *
 * @ORM\Table(name="parabol_seo")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\SeoRepository")
 * @UniqueEntity(fields={"url", "inherited"}, errorPath="url")
 */
class Seo extends \Parabol\AdminCoreBundle\Model\Seo
{
}
