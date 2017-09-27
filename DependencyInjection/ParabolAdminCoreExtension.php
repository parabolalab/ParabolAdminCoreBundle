<?php

namespace Parabol\AdminCoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ParabolAdminCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('parabol_admin_core.post.list_redirected', $config['post']['list_redirected']);    
        $container->setParameter('parabol_admin_core.post.disabled', $config['post']['disabled']);

        $container->setParameter('parabol_admin_core.dashboard.redirected', $config['dashboard']['redirected']);
        $container->setParameter('parabol_admin_core.dashboard.disabled', $config['dashboard']['disabled']);

        $container->setParameter('parabol_admin_core.text_block.disabled', $config['text_block']['disabled']);

        $container->setParameter('parabol_admin_core.gallery.disabled', $config['gallery']['disabled']);

        $container->setParameter('parabol_admin_core.menu.disabled', $config['menu']['disabled']);

        $container->setParameter('parabol_admin_core.app_setting.disabled', $config['app_setting']['disabled']);

        $container->setParameter('parabol_admin_core.admin_menu', $config['admin_menu']);
        // var_dump();
        // die();

        $container->setParameter('web_dir', 'web');
        $container->setParameter('ext_choice.choices', array());
        $container->setParameter('locales', array('pl'));

        $container->setParameter('google.view_id', null);

        $container->setParameter('default_users', array(
            'aliso' => array(
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => 'admin',
            )
        ));
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
  
}
