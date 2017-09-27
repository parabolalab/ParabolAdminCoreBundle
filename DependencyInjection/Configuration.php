<?php

namespace Parabol\AdminCoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('parabol_admin_core');

        $rootNode
            ->children()
                ->arrayNode('post')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('list_redirected')->defaultValue(false)->end()
                        ->scalarNode('disabled')->defaultValue(false)->end()
                    ->end() 
                ->end()  
                ->arrayNode('dashboard')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('redirected')->defaultValue(false)->end()
                        ->scalarNode('disabled')->defaultValue(false)->end()
                    ->end() 
                ->end()
                ->arrayNode('text_block')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('disabled')->defaultValue(false)->end()
                    ->end() 
                ->end()  
                ->arrayNode('gallery')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('disabled')->defaultValue(false)->end()
                    ->end() 
                ->end() 
                ->arrayNode('menu')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('disabled')->defaultValue(false)->end()
                    ->end() 
                ->end()    
                ->arrayNode('app_setting')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('disabled')->defaultValue(false)->end()
                    ->end() 
                ->end()
                ->arrayNode('admin_menu')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('route')->end()
                            ->scalarNode('icon')->end()
                            ->scalarNode('parent')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

                             

        return $treeBuilder;
    }
}
