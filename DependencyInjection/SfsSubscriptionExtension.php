<?php

namespace Softspring\SubscriptionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SfsSubscriptionExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('sfs_subscription.entity_manager_name', $config['entity_manager']);

        $container->setParameter('sfs_subscription.client.class', $config['model']['client']);
        $container->setParameter('sfs_subscription.invoice.class', $config['model']['invoice']);
        $container->setParameter('sfs_subscription.plan.class', $config['model']['plan']);
        $container->setParameter('sfs_subscription.product.class', $config['model']['product']);
        $container->setParameter('sfs_subscription.subscription.class', $config['model']['subscription']);

        // load services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));

        if ($config['adapter']['driver'] == 'stripe') {
            $container->setParameter('sfs_subscription.adapter.stripe.options', $config['adapter']['options']);
            $loader->load('adapter/stripe.yaml');
        }

        $loader->load('services.yaml');
        $loader->load('controller/admin_plans.yaml');
        $loader->load('controller/admin_products.yaml');
    }

}