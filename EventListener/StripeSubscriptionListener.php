<?php

namespace Softspring\SubscriptionBundle\EventListener;

use Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent;
use Softspring\SubscriptionBundle\Manager\ApiManagerInterface;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class StripeSubscriptionListener implements EventSubscriberInterface
{
    /**
     * @var ApiManagerInterface
     */
    protected $api;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * StripeSubscriptionListener constructor.
     * @param ApiManagerInterface $api
     * @param RouterInterface $router
     */
    public function __construct(ApiManagerInterface $api, RouterInterface $router)
    {
        $this->api = $api;
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_INITIALIZE => [
                ['onSubscribeInitializeEnsureClientPayment', 0]
            ],
        ];
    }

    /**
     * @param PreSubscribeGetResponseEvent $event
     * @throws \Exception
     */
    public function onSubscribeInitializeEnsureClientPayment(PreSubscribeGetResponseEvent $event)
    {
        if ($this->api->name() !== 'stripe') {
            throw new \Exception('This listener should not be instanced with any other driver');
        }

        $client = $event->getClient();
        $plan = $event->getPlan();

        $clientData = $this->api->client()->getClientData($client);

        if (!$clientData->default_source) {
            $url = $this->router->generate('sfs_subscription_subscribe_add_card', ['_account'=>$client, 'plan' => $plan]);
            $event->setResponse(new RedirectResponse($url));
        }
    }
}