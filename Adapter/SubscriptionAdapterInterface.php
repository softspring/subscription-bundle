<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\Subscription\Model\ClientInterface;
use Softspring\Subscription\Model\PlanInterface;
use Softspring\Subscription\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;

interface SubscriptionAdapterInterface
{
    /**
     * @param SubscriptionInterface $subscription
     * @param ClientInterface $client
     * @param PlanInterface $plan
     * @throws SubscriptionException
     */
    public function subscribe(SubscriptionInterface $subscription, ClientInterface $client, PlanInterface $plan): void;

    /**
     * @param SubscriptionInterface $subscription
     * @param ClientInterface $client
     * @param PlanInterface $plan
     * @throws SubscriptionException
     */
    public function trial(SubscriptionInterface $subscription, ClientInterface $client, PlanInterface $plan): void;

    /**
     * @param SubscriptionInterface $subscription
     * @return array
     * @throws SubscriptionException
     */
    public function details(SubscriptionInterface $subscription): array;

    /**
     * @param SubscriptionInterface $subscription
     * @return array
     * @throws SubscriptionException
     */
    public function cancel(SubscriptionInterface $subscription): array;

    /**
     * @param SubscriptionInterface $subscription
     * @return array
     * @throws SubscriptionException
     */
    public function uncancel(SubscriptionInterface $subscription): array;

    /**
     * @param SubscriptionInterface $subscription
     * @param PlanInterface $plan
     * @return array
     * @throws SubscriptionException
     */
    public function upgrade(SubscriptionInterface $subscription, PlanInterface $plan): array;
}