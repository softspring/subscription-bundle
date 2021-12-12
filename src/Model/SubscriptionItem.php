<?php

namespace Softspring\SubscriptionBundle\Model;

abstract class SubscriptionItem implements SubscriptionItemInterface
{
    /**
     * @var SubscriptionInterface|null
     */
    protected $subscription;

    /**
     * @var PlanInterface|null
     */
    protected $plan;

    /**
     * @var int
     */
    protected $quantity = 1;

    /**
     * @return SubscriptionInterface|null
     */
    public function getSubscription(): ?SubscriptionInterface
    {
        return $this->subscription;
    }

    /**
     * @param SubscriptionInterface|null $subscription
     */
    public function setSubscription(?SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }

    /**
     * @return PlanInterface|null
     */
    public function getPlan(): ?PlanInterface
    {
        return $this->plan;
    }

    /**
     * @param PlanInterface|null $plan
     */
    public function setPlan(?PlanInterface $plan): void
    {
        $this->plan = $plan;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}