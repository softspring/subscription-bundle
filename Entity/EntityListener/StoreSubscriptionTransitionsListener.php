<?php

namespace Softspring\SubscriptionBundle\Entity\EntityListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Softspring\SubscriptionBundle\Manager\SubscriptionTransitionManagerInterface;
use Softspring\SubscriptionBundle\Model\Subscription;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;

class StoreSubscriptionTransitionsListener
{
    /**
     * @var SubscriptionTransitionManagerInterface
     */
    protected $transtionManager;

    /**
     * StoreSubscriptionTransitionsListener constructor.
     *
     * @param SubscriptionTransitionManagerInterface $transtionManager
     */
    public function __construct(SubscriptionTransitionManagerInterface $transtionManager)
    {
        $this->transtionManager = $transtionManager;
    }

    public function postLoad(Subscription $subscription, LifecycleEventArgs $eventArgs)
    {
        $subscription = $eventArgs->getObject();

        if (!$subscription instanceof SubscriptionInterface) {
            return;
        }
    }

    /**
     * @param PreUpdateEventArgs $eventArgs
     *
     * @throws \Exception
     */
    public function preUpdate(Subscription $subscription, PreUpdateEventArgs $eventArgs)
    {
        if (!$eventArgs->hasChangedField('status')) {
            return;
        }

        /** @var SubscriptionTransitionInterface $transition */
        $transition = $this->transtionManager->createEntity();
        $transition->setStatus($eventArgs->getNewValue('status'));
        $transition->setDate(new \DateTime('now'));
        $subscription->addTransition($transition);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     *
     * @throws \Exception
     */
    public function prePersist(Subscription $subscription, LifecycleEventArgs $eventArgs)
    {
        /** @var SubscriptionTransitionInterface $transition */
        $transition = $this->transtionManager->createEntity();
        $transition->setStatus($subscription->getStatus());
        $transition->setDate(new \DateTime('now'));
        $subscription->addTransition($transition);
    }
}