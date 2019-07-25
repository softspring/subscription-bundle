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

                ->arrayNode('adapter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('driver')
                            ->defaultValue('stripe')
                            ->values(['stripe'])
                        ->end()
                        ->variableNode('options')->defaultValue([])->end()
                    ->end()
                ->end()

                ->arrayNode('model')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('client')->defaultValue('App\Entity\Client')->end()
                        ->scalarNode('invoice')->defaultValue('App\Entity\Invoice')->end()
                        ->scalarNode('plan')->defaultValue('App\Entity\Plan')->end()
                        ->scalarNode('product')->defaultValue('App\Entity\Product')->end()
                        ->scalarNode('subscription')->defaultValue('App\Entity\Subscription')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}