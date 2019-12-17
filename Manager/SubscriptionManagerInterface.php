<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\AdminBundle\Manager\AdminEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;

interface SubscriptionManagerInterface extends AdminEntityManagerInterface
{
    /**
     * @param SubscriptionCustomerInterface $client
     * @param PlanInterface                 $plan
     *
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function subscribe(SubscriptionCustomerInterface $client, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param SubscriptionCustomerInterface $customer
     * @param PlanInterface                 $plan
     * @param int                           $days
     *
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function trial(SubscriptionCustomerInterface $customer, PlanInterface $plan, int $days): SubscriptionInterface;

    /**
     * @param SubscriptionCustomerInterface $client
     * @param SubscriptionInterface         $subscription
     *
     * @throws SubscriptionException
     */
    public function cancel(SubscriptionCustomerInterface $client, SubscriptionInterface $subscription): void;

    /**
     * @param SubscriptionCustomerInterface $client
     * @param SubscriptionInterface         $subscription
     *
     * @throws SubscriptionException
     */
    public function uncancel(SubscriptionCustomerInterface $client, SubscriptionInterface $subscription): void;

    /**
     * @param SubscriptionCustomerInterface $client
     * @param SubscriptionInterface         $subscription
     * @param PlanInterface                 $plan
     *
     * @throws SubscriptionException
     */
    public function upgrade(SubscriptionCustomerInterface $client, SubscriptionInterface $subscription, PlanInterface $plan): void;

    /**
     * @param SubscriptionCustomerInterface $client
     * @param SubscriptionInterface         $subscription
     * @param PlanInterface                 $plan
     *
     * @throws SubscriptionException
     */
    public function finishTrial(SubscriptionCustomerInterface $client, SubscriptionInterface $subscription, PlanInterface $plan): void;
}