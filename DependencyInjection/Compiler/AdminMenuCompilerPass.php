<?php

namespace Parabol\AdminCoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class AdminMenuCompilerPass implements CompilerPassInterface
{
	public function __construct()
	{
		
	}

    public function process(ContainerBuilder $container)
    {
    	if (false === $container->hasDefinition('parabol.admin_menu')) {
            return;
        }

        $definition = $container->getDefinition('parabol.admin_menu');

        foreach ($container->findTaggedServiceIds('parabol.admin_menu') as $id => $attributes) {
            $definition->addMethodCall('addItem', array(new Reference($id), $id));
        }
    }
}