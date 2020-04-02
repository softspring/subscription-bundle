<?php

namespace Softspring\SubscriptionBundle\EventListener;

use Softspring\CustomerBundle\Event\NotifyEvent;
use Softspring\CustomerBundle\Platform\PlatformInterface;
use Softspring\CustomerBundle\SfsCustomerEvents;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Softspring\SubscriptionBundle\Platform\Response\SubscriptionResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StripeSubscriptionsNotifyListener implements EventSubscriberInterface
{
    /**
     * @var SubscriptionManagerInterface
     */
    protected $subscriptionManager;

    /**
     * StripeSubscriptionsNotifyListener constructor.
     *
     * @param SubscriptionManagerInterface $subscriptionManager
     */
    public function __construct(SubscriptionManagerInterface $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SfsCustomerEvents::NOTIFY => [['onNotify', 0]],
        ];
    }

    /**
     * @param NotifyEvent $event
     *
     * @throws \Softspring\CustomerBundle\Platform\Exception\PlatformNotYetImplemented
     * @throws \Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException
     */
    public function onNotify(NotifyEvent $event)
    {
        if ($event->getPlatform() !== PlatformInterface::PLATFORM_STRIPE) {
            throw new \Exception('This listener should not be instanced with any other driver');
        }

        $supportedEvents = [
            // Occurs whenever a customer is signed up for a new plan.
            'customer.subscription.created',

            // Occurs whenever a customer's subscription ends.
            'customer.subscription.deleted',

            // Occurs three days before a subscription's trial period is scheduled to end, or when a trial is ended immediately (using trial_end=now).
            'customer.subscription.trial_will_end',

            // Occurs whenever a subscription changes (e.g., switching from one plan to another, or changing the status from trial to active).
            'customer.subscription.updated',
        ];

        if (!in_array($event->getName(), $supportedEvents)) {
            return;
        }

        $subscriptionResponse = new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $event->getData()->data->object);

        if (!$subscription = $this->subscriptionManager->getRepository()->findOneByPlatformId($subscriptionResponse->getId())) {
            return;
        }

        $this->subscriptionManager->updateFromPlatform($subscription, $subscriptionResponse);
    }
}