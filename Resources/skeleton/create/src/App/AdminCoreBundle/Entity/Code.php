<?php

namespace App\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seo
 *
 * @ORM\Table(name="parabol_code")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\CodeRepository")
 */
class Code extends \Parabol\AdminCoreBundle\Model\Code
{
}
