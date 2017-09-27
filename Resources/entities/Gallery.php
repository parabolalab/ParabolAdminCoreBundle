<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Gallery
 *
 * @ORM\Table(name="parabol_gallery")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\GalleryRepository")
 */
class Gallery extends \Parabol\AdminCoreBundle\Model\Gallery
{

}
