<?php

namespace Parabol\AdminCoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class AdminBaseTypeExtensionCompilerPass implements CompilerPassInterface
{
	public function __construct()
	{
		
	}

    public function process(ContainerBuilder $container)
    {
    	if (false === $container->hasDefinition('parabol.admin_base_type_extension')) {
            return;
        }

        $definition = $container->getDefinition('parabol.admin_base_type_extension');

        foreach ($container->findTaggedServiceIds('parabol.admin_base_type_extension') as $id => $attributes) {
            $definition->addMethodCall('addExtension', array(new Reference($id)));
        }
    }
}