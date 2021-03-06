<?php

namespace Softspring\SubscriptionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sfs_subscription');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('entity_manager')
                    ->defaultValue('default')
                ->end()

                ->arrayNode('model')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('invoice')->defaultNull()->end()
                        ->scalarNode('plan')->defaultValue('App\Entity\Plan')->end()
                        ->scalarNode('product')->defaultNull()->end()
                        ->scalarNode('subscription')->defaultValue('App\Entity\Subscription')->end()
                        ->scalarNode('subscription_item')->defaultNull()->end()
                        ->scalarNode('subscription_transition')->defaultValue('App\Entity\SubscriptionTransition')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}