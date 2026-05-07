<?php

namespace Softspring\SubscriptionBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\DependencyInjection\SfsSubscriptionExtension;
use Softspring\SubscriptionBundle\Request\ValueResolver\PlanValueResolver;
use Softspring\SubscriptionBundle\Request\ValueResolver\SubscriptionValueResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SfsSubscriptionExtensionTest extends TestCase
{
    public function testLoadRegistersConfiguredParametersAndValueResolvers(): void
    {
        $container = new ContainerBuilder();
        $extension = new SfsSubscriptionExtension();

        $extension->load([[
            'entity_manager' => 'subscriptions',
            'model' => [
                'invoice' => 'App\\Entity\\Invoice',
                'plan' => 'App\\Entity\\Plan',
                'product' => 'App\\Entity\\Product',
                'subscription' => 'App\\Entity\\Subscription',
                'subscription_item' => 'App\\Entity\\SubscriptionItem',
                'subscription_transition' => 'App\\Entity\\SubscriptionTransition',
            ],
        ]], $container);

        self::assertSame('subscriptions', $container->getParameter('sfs_subscription.entity_manager_name'));
        self::assertSame('App\\Entity\\Invoice', $container->getParameter('sfs_subscription.invoice.class'));
        self::assertSame('App\\Entity\\Plan', $container->getParameter('sfs_subscription.plan.class'));
        self::assertSame('App\\Entity\\Product', $container->getParameter('sfs_subscription.product.class'));
        self::assertSame('App\\Entity\\Subscription', $container->getParameter('sfs_subscription.subscription.class'));
        self::assertSame('App\\Entity\\SubscriptionItem', $container->getParameter('sfs_subscription.subscription_item.class'));
        self::assertSame('App\\Entity\\SubscriptionTransition', $container->getParameter('sfs_subscription.subscription_transition.class'));

        self::assertTrue($container->hasDefinition(PlanValueResolver::class));
        self::assertTrue($container->hasDefinition(SubscriptionValueResolver::class));
        self::assertTrue($container->hasDefinition('sfs_subscription.admin.product.controller'));
    }

    public function testLoadSkipsOptionalProductServicesWhenProductModelIsMissing(): void
    {
        $container = new ContainerBuilder();
        $extension = new SfsSubscriptionExtension();

        $extension->load([[
            'entity_manager' => 'default',
            'model' => [
                'invoice' => null,
                'plan' => 'App\\Entity\\Plan',
                'product' => null,
                'subscription' => 'App\\Entity\\Subscription',
                'subscription_item' => null,
                'subscription_transition' => 'App\\Entity\\SubscriptionTransition',
            ],
        ]], $container);

        self::assertFalse($container->hasDefinition('sfs_subscription.admin.product.controller'));
    }
}
