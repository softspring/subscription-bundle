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
}