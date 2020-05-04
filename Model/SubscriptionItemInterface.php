<?php

namespace Softspring\SubscriptionBundle\Model;

use Softspring\CustomerBundle\Model\PlatformObjectInterface;

interface SubscriptionItemInterface extends PlatformObjectInterface
{
    /**
     * @return SubscriptionInterface|null
     */
    public function getSubscription(): ?SubscriptionInterface;

    /**
     * @param SubscriptionInterface|null $subscription
     */
    public function setSubscription(?SubscriptionInterface $subscription): void;

    /**
     * @return PlanInterface|null
     */
    public function getPlan(): ?PlanInterface;

    /**
     * @param PlanInterface|null $plan
     */
    public function setPlan(?PlanInterface $plan): void;

    /**
     * @return int
     */
    public function getQuantity(): int;

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void;
}