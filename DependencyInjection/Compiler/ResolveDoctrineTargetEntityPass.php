<?php

namespace Softspring\SubscriptionBundle\DependencyInjection\Compiler;

use Softspring\CoreBundle\DependencyInjection\Compiler\AbstractResolveDoctrineTargetEntityPass;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\InvoiceInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\ProductInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveDoctrineTargetEntityPass extends AbstractResolveDoctrineTargetEntityPass
{
    /**
     * @inheritDoc
     */
    protected function getEntityManagerName(ContainerBuilder $container): string
    {
        return $container->getParameter('sfs_subscription.entity_manager_name');
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->setTargetEntityFromParameter('sfs_subscription.invoice.class', InvoiceInterface::class, $container, false);
        $this->setTargetEntityFromParameter('sfs_subscription.plan.class', PlanInterface::class, $container, true);
        $this->setTargetEntityFromParameter('sfs_subscription.product.class', ProductInterface::class, $container, false);
        $this->setTargetEntityFromParameter('sfs_subscription.subscription.class', SubscriptionInterface::class, $container, true);
        $this->setTargetEntityFromParameter('sfs_subscription.subscription_transition.class', SubscriptionTransitionInterface::class, $container, false);
    }
}