<?php

namespace Softspring\SubscriptionBundle\DependencyInjection\Compiler;

use Softspring\Subscription\Model\ClientInterface;
use Softspring\Subscription\Model\InvoiceInterface;
use Softspring\Subscription\Model\PlanInterface;
use Softspring\Subscription\Model\ProductInterface;
use Softspring\Subscription\Model\SubscriptionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class ResolveDoctrineTargetEntityPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->setTargetEntityFromParameter('sfs_subscription.client.class', ClientInterface::class, $container, true);
        $this->setTargetEntityFromParameter('sfs_subscription.invoice.class', InvoiceInterface::class, $container, false);
        $this->setTargetEntityFromParameter('sfs_subscription.plan.class', PlanInterface::class, $container, true);
        $this->setTargetEntityFromParameter('sfs_subscription.product.class', ProductInterface::class, $container, true);
        $this->setTargetEntityFromParameter('sfs_subscription.subscription.class', SubscriptionInterface::class, $container, true);
    }

    protected function setTargetEntityFromParameter(string $parameterName, string $interface, ContainerBuilder $container, bool $required = true)
    {
        if ($class = $container->getParameter($parameterName)) {
            if (!class_implements($class, $interface)) {
                throw new LogicException(sprintf('%s class must implements %s interface', $class, $interface));
            }

            $this->setTargetEntity($container, $interface, $class);
        } else {
            if ($required) {
                throw new InvalidArgumentException(sprintf('%s parameter must be a valid entity', $parameterName));
            }
        }
    }

    private function setTargetEntity(ContainerBuilder $container, string $interface, string $class)
    {
        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_subscriber')) {
            $resolveTargetEntityListener->addTag('doctrine.event_subscriber');
        }

        $resolveTargetEntityListener->addMethodCall('addResolveTargetEntity', [$interface, $class, [$container->getParameter('sfs_subscription.entity_manager_name')]]);
    }
}