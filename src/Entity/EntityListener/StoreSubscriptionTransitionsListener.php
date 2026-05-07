<?php

namespace Softspring\SubscriptionBundle\Entity\EntityListener;

use DateTime;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;
use Softspring\SubscriptionBundle\Manager\SubscriptionTransitionManagerInterface;
use Softspring\SubscriptionBundle\Model\Subscription;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;

class StoreSubscriptionTransitionsListener
{
    public function __construct(protected SubscriptionTransitionManagerInterface $transtionManager)
    {
    }

    public function postLoad(Subscription $subscription, LifecycleEventArgs $eventArgs): void
    {
        $eventArgs->getObject();
    }

    /**
     * @throws Exception
     */
    public function preUpdate(Subscription $subscription, PreUpdateEventArgs $eventArgs): void
    {
        if (!$eventArgs->hasChangedField('status')) {
            return;
        }

        /** @var SubscriptionTransitionInterface $transition */
        $transition = $this->transtionManager->createEntity();
        $transition->setStatus($eventArgs->getNewValue('status'));
        $transition->setDate(new DateTime('now'));
        $subscription->addTransition($transition);
    }

    /**
     * @throws Exception
     */
    public function prePersist(Subscription $subscription, LifecycleEventArgs $eventArgs): void
    {
        /** @var SubscriptionTransitionInterface $transition */
        $transition = $this->transtionManager->createEntity();
        $transition->setStatus($subscription->getStatus());
        $transition->setDate(new DateTime('now'));
        $subscription->addTransition($transition);
    }
}
