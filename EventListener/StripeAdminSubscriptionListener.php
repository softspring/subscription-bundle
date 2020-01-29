<?php

namespace Softspring\SubscriptionBundle\EventListener;

use Softspring\CoreBundle\Event\ViewEvent;
use Softspring\CustomerBundle\Manager\ApiManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StripeAdminSubscriptionListener implements EventSubscriberInterface
{
    /**
     * @var ApiManagerInterface
     */
    protected $api;

    /**
     * StripeAdminSubscriptionListener constructor.
     * @param ApiManagerInterface $api
     */
    public function __construct(ApiManagerInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SfsSubscriptionEvents::ADMIN_SUBSCRIPTIONS_READ_VIEW => [
                ['onReadView', 0]
            ],
        ];
    }

    /**
     * @param ViewEvent $event
     * @throws \Exception
     */
    public function onReadView(ViewEvent $event)
    {
        if ($this->api->name() !== 'stripe') {
            throw new \Exception('This listener should not be instanced with any other driver');
        }

        $data = $event->getData();
        $subscription = $data['subscription'];

        if (!$subscription instanceof SubscriptionInterface) {
            return;
        }

        $data['stripe_subscription_data'] = $this->api->get('subscription')->details($subscription);
    }
}