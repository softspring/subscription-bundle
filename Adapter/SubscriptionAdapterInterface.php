<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\SubscriptionBundle\Model\CustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;

interface SubscriptionAdapterInterface extends PlatformAdapterInterface
{
    /**
     * @param SubscriptionInterface $subscription
     * @param CustomerInterface     $client
     * @param PlanInterface         $plan
     *
     * @throws SubscriptionException
     */
    public function subscribe(SubscriptionInterface $subscription, CustomerInterface $client, PlanInterface $plan): void;

    /**
     * @param SubscriptionInterface $subscription
     * @param CustomerInterface     $client
     * @param PlanInterface         $plan
     *
     * @throws SubscriptionException
     */
    public function trial(SubscriptionInterface $subscription, CustomerInterface $client, PlanInterface $plan): void;

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

    /**
     * @param SubscriptionInterface $subscription
     * @param PlanInterface $plan
     * @throws SubscriptionException
     */
    public function finishTrial(SubscriptionInterface $subscription, PlanInterface $plan): void;
}