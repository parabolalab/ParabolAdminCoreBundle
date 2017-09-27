<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends \Parabol\AdminCoreBundle\Model\User
{

}
