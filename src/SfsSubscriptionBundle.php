<?php

namespace Softspring\SubscriptionBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Softspring\SubscriptionBundle\DependencyInjection\Compiler\AliasDoctrineEntityManagerPass;
use Softspring\SubscriptionBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsSubscriptionBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $basePath = realpath(__DIR__.'/../config/doctrine-mapping/');

        $this->addRegisterMappingsPass($container, [$basePath => 'Softspring\SubscriptionBundle\Model']);

        $container->addCompilerPass(new AliasDoctrineEntityManagerPass());
        $container->addCompilerPass(new ResolveDoctrineTargetEntityPass());
    }

    private function addRegisterMappingsPass(ContainerBuilder $container, array $mappings, string|bool $enablingParameter = false): void
    {
        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['sfs_subscription.entity_manager_name'], $enablingParameter));
    }
}
