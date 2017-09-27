<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="parabol_post")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\PostRepository")
 */
class Post extends \Parabol\AdminCoreBundle\Model\Post
{

}
