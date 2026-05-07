<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;

interface SubscriptionManagerInterface extends CrudlEntityManagerInterface
{
    public function getItemManager(): SubscriptionItemManagerInterface;

    /**
     * @throws SubscriptionException
     */
    public function subscribe(SubscriptionCustomerInterface $customer, PlanInterface $plan, int $quantity = 1): SubscriptionInterface;

    /**
     * @throws SubscriptionException
     */
    public function trial(SubscriptionCustomerInterface $customer, PlanInterface $plan, int $days): SubscriptionInterface;

    /**
     * @throws SubscriptionException
     */
    public function cancel(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * @throws SubscriptionException
     */
    public function sync(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * @throws SubscriptionException
     */
    public function cancelRenovation(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * @throws SubscriptionException
     */
    public function uncancelRenovation(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * @throws SubscriptionException
     */
    public function upgrade(SubscriptionInterface $subscription, PlanInterface $plan): SubscriptionInterface;

    /**
     * @throws SubscriptionException
     */
    public function finishTrial(SubscriptionInterface $subscription, PlanInterface $plan): SubscriptionInterface;

    /**
     * @return SubscriptionInterface
     */
    public function createEntity(): object;

    /**
     * @param SubscriptionInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @param SubscriptionInterface $entity
     */
    public function deleteEntity(object $entity): void;
}
