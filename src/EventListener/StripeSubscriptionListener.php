<?php

namespace Softspring\SubscriptionBundle\EventListener;

use Softspring\PlatformBundle\Manager\AdapterManagerInterface;
use Softspring\CustomerBundle\Manager\CustomerManagerInterface;
use Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent;
use Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class StripeSubscriptionListener implements EventSubscriberInterface
{
    /**
     * @var AdapterManagerInterface
     */
    protected $api;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var CustomerManagerInterface
     */
    protected $customerManager;

    /**
     * StripeSubscriptionListener constructor.
     *
     * @param AdapterManagerInterface  $api
     * @param RouterInterface          $router
     * @param CustomerManagerInterface $customerManager
     */
    public function __construct(AdapterManagerInterface $api, RouterInterface $router, CustomerManagerInterface $customerManager)
    {
        $this->api = $api;
        $this->router = $router;
        $this->customerManager = $customerManager;
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
            SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_INITIALIZE => [
                ['onUpgradeInitializeEnsureClientPayment', 0]
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

//        if (!$client->getPlatformId()) {
//            // $this->customerManager->createInPlatform($client);
//        }

        $plan = $event->getPlan();

        $clientData = $this->api->get('customer')->get($client);

        if (!$clientData->default_source && $plan->getAmount()) {
            $url = $this->router->generate('sfs_subscription_subscribe_add_card', ['plan' => $plan]);
            $event->setResponse(new RedirectResponse($url));
        }
    }

    /**
     * @param UpgradeGetResponseEvent $event
     * @throws \Exception
     */
    public function onUpgradeInitializeEnsureClientPayment(UpgradeGetResponseEvent $event)
    {
        if ($this->api->name() !== 'stripe') {
            throw new \Exception('This listener should not be instanced with any other driver');
        }

        $client = $event->getSubscription()->getCustomer();

//        if (!$client->getPlatformId()) {
//            // $this->customerManager->createInPlatform($client);
//        }

        $plan = $event->getNewPlan();

        $clientData = $this->api->get('customer')->get($client);

        if (!$clientData->default_source) {
            $url = $this->router->generate('sfs_subscription_subscribe_add_card', ['_account'=>$client, 'plan' => $plan]);
            $event->setResponse(new RedirectResponse($url));
        }
    }
}