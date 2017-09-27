<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuItem
 *
 * @ORM\Table(name="parabol_menu_item")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\MenuItemRepository")
 */
class MenuItem extends \Parabol\AdminCoreBundle\Model\MenuItem
{
    
}
