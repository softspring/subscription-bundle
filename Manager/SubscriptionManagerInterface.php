<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\CrudlBundle\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Platform\Response\SubscriptionResponse;

interface SubscriptionManagerInterface extends CrudlEntityManagerInterface
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
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionException
     */
    public function cancelRenovation(SubscriptionInterface $subscription): void;

    /**
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionException
     */
    public function uncancelRenovation(SubscriptionInterface $subscription): void;

    /**
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionException
     */
    public function sync(SubscriptionInterface $subscription): void;

    /**
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionException
     */
    public function cancel(SubscriptionInterface $subscription): void;

    /**
     * @param SubscriptionInterface $subscription
     * @param PlanInterface         $plan
     *
     * @throws SubscriptionException
     */
    public function upgrade(SubscriptionInterface $subscription, PlanInterface $plan): void;

    /**
     * @param SubscriptionInterface $subscription
     * @param PlanInterface         $plan
     *
     * @throws SubscriptionException
     */
    public function finishTrial(SubscriptionInterface $subscription, PlanInterface $plan): void;

    /**
     * @param SubscriptionInterface $subscription
     * @param SubscriptionResponse  $subscriptionResponse
     *
     * @return SubscriptionInterface
     */
    public function updateFromPlatform(SubscriptionInterface $subscription, SubscriptionResponse $subscriptionResponse): SubscriptionInterface;
}