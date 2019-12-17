<?php

namespace Softspring\SubscriptionBundle\EventListener;

use Softspring\CustomerBundle\Event\NotifyEvent;
use Softspring\CustomerBundle\PlatformInterface;
use Softspring\CustomerBundle\SfsCustomerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StripeNotifyListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            SfsCustomerEvents::NOTIFY => [['onNotify', 0]],
        ];
    }

    public function onNotify(NotifyEvent $event)
    {
        if ($event->getPlatform() !== PlatformInterface::PLATFORM_STRIPE) {
            throw new \Exception('This listener should not be instanced with any other driver');
        }

        // https://stripe.com/docs/api/events/list
    }
}