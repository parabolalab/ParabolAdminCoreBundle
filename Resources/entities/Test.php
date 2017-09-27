<?php

namespace Parabol\AdminCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 *
 * @ORM\Table(name="parabol_test")
 * @ORM\Entity(repositoryClass="Parabol\AdminCoreBundle\Repository\TestRepository")
 */
class Test extends \Parabol\AdminCoreBundle\Model\Test
{
    
}
