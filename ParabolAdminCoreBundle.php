<?php

namespace Parabol\AdminCoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use Parabol\AdminCoreBundle\Model;

class ParabolAdminCoreBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
    	$mappings = array(
            realpath(__DIR__ . '/Resources/config/doctrine-mapping') => Model::class,
        );

        if (class_exists(DoctrineOrmMappingsPass::class)) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createYamlMappingDriver($mappings, array('parabol_admin_core.model_manager_name'), 'parabol_admin_core.backend_type_orm', array('ParabolAdminCoreBundle' => Model::class)));
        }

        if (class_exists(DoctrineMongoDBMappingsPass::class)) {
            $container->addCompilerPass(DoctrineMongoDBMappingsPass::createYamlMappingDriver($mappings, array('parabol_admin_core.model_manager_name'), 'parabol_admin_core.backend_type_mongodb', array('ParabolAdminCoreBundle' => Model::class)));
        }
    }
}
