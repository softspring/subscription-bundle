<?php

namespace Softspring\SubscriptionBundle\EventListener;

use Exception;
use Softspring\CustomerBundle\Manager\CustomerManagerInterface;
use Softspring\PlatformBundle\Manager\AdapterManagerInterface;
use Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent;
use Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class StripeSubscriptionListener implements EventSubscriberInterface
{
    public function __construct(
        protected AdapterManagerInterface $api,
        protected RouterInterface $router,
        protected CustomerManagerInterface $customerManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_INITIALIZE => [
                ['onSubscribeInitializeEnsureClientPayment', 0],
            ],
            SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_INITIALIZE => [
                ['onUpgradeInitializeEnsureClientPayment', 0],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function onSubscribeInitializeEnsureClientPayment(PreSubscribeGetResponseEvent $event): void
    {
        if ('stripe' !== $this->api->name()) {
            throw new Exception('This listener should not be instanced with any other driver');
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
     * @throws Exception
     */
    public function onUpgradeInitializeEnsureClientPayment(UpgradeGetResponseEvent $event): void
    {
        if ('stripe' !== $this->api->name()) {
            throw new Exception('This listener should not be instanced with any other driver');
        }

        $client = $event->getSubscription()->getCustomer();

        //        if (!$client->getPlatformId()) {
        //            // $this->customerManager->createInPlatform($client);
        //        }

        $plan = $event->getNewPlan();

        $clientData = $this->api->get('customer')->get($client);

        if (!$clientData->default_source) {
            $url = $this->router->generate('sfs_subscription_subscribe_add_card', ['_account' => $client, 'plan' => $plan]);
            $event->setResponse(new RedirectResponse($url));
        }
    }
}
