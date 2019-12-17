<?php

namespace Softspring\SubscriptionBundle\Entity\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;

class StoreSubscriptionTransitionsListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::preUpdate,
            Events::prePersist,
        );
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $subscription = $args->getObject();

        if (!$subscription instanceof SubscriptionInterface) {
            return;
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $subscription = $args->getObject();

        if (!$subscription instanceof SubscriptionInterface) {
            return;
        }
    }
}