<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parabol_path", uniqueConstraints={@ORM\UniqueConstraint(name="path_unique_idx", columns={"class", "object_id"})})
 */
class Path extends \Parabol\AdminCoreBundle\Model\Path
{
    
}
