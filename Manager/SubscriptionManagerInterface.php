<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\CrudlBundle\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;

interface SubscriptionManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return SubscriptionItemManagerInterface
     */
    public function getItemManager(): SubscriptionItemManagerInterface;

    /**
     * @param SubscriptionCustomerInterface $customer
     * @param PlanInterface                 $plan
     * @param int                           $quantity
     *
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function subscribe(SubscriptionCustomerInterface $customer, PlanInterface $plan, int $quantity = 1): SubscriptionInterface;

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
    public function cancel(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionException
     */
    public function sync(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionException
     */
    public function cancelRenovation(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionException
     */
    public function uncancelRenovation(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * @param SubscriptionInterface $subscription
     * @param PlanInterface         $plan
     *
     * @throws SubscriptionException
     */
    public function upgrade(SubscriptionInterface $subscription, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param SubscriptionInterface $subscription
     * @param PlanInterface         $plan
     *
     * @throws SubscriptionException
     */
    public function finishTrial(SubscriptionInterface $subscription, PlanInterface $plan): SubscriptionInterface;

    /**
     * @return SubscriptionInterface
     */
    public function createEntity();

    /**
     * @param SubscriptionInterface $entity
     */
    public function saveEntity($entity): void;

    /**
     * @param SubscriptionInterface $entity
     */
    public function deleteEntity($entity): void;
}