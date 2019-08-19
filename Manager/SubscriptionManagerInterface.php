<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\AdminBundle\Manager\AdminEntityManagerInterface;
use Softspring\Subscription\Model\ClientInterface;
use Softspring\Subscription\Model\PlanInterface;
use Softspring\Subscription\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;

interface SubscriptionManagerInterface extends AdminEntityManagerInterface
{
    /**
     * @param ClientInterface $client
     * @param PlanInterface $plan
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function subscribe(ClientInterface $client, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param ClientInterface $client
     * @param PlanInterface $plan
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function trial(ClientInterface $client, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param ClientInterface $client
     * @param SubscriptionInterface $subscription
     * @throws SubscriptionException
     */
    public function cancel(ClientInterface $client, SubscriptionInterface $subscription): void;

    /**
     * @param ClientInterface $client
     * @param SubscriptionInterface $subscription
     * @throws SubscriptionException
     */
    public function uncancel(ClientInterface $client, SubscriptionInterface $subscription): void;

    /**
     * @param ClientInterface $client
     * @param SubscriptionInterface $subscription
     * @param PlanInterface $plan
     * @throws SubscriptionException
     */
    public function upgrade(ClientInterface $client, SubscriptionInterface $subscription, PlanInterface $plan): void;
}