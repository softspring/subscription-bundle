<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\AdminBundle\Manager\AdminEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\CustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;

interface SubscriptionManagerInterface extends AdminEntityManagerInterface
{
    /**
     * @param CustomerInterface $client
     * @param PlanInterface     $plan
     *
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function subscribe(CustomerInterface $client, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param CustomerInterface $client
     * @param PlanInterface     $plan
     *
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function trial(CustomerInterface $client, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param CustomerInterface     $client
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionException
     */
    public function cancel(CustomerInterface $client, SubscriptionInterface $subscription): void;

    /**
     * @param CustomerInterface $client
     * @param SubscriptionInterface $subscription
     * @throws SubscriptionException
     */
    public function uncancel(CustomerInterface $client, SubscriptionInterface $subscription): void;

    /**
     * @param CustomerInterface $client
     * @param SubscriptionInterface $subscription
     * @param PlanInterface $plan
     * @throws SubscriptionException
     */
    public function upgrade(CustomerInterface $client, SubscriptionInterface $subscription, PlanInterface $plan): void;

    /**
     * @param CustomerInterface $client
     * @param SubscriptionInterface $subscription
     * @param PlanInterface $plan
     * @throws SubscriptionException
     */
    public function finishTrial(CustomerInterface $client, SubscriptionInterface $subscription, PlanInterface $plan): void;
}