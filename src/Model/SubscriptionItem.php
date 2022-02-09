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

    public function getSubscription(): ?SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(?SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }

    public function getPlan(): ?PlanInterface
    {
        return $this->plan;
    }

    public function setPlan(?PlanInterface $plan): void
    {
        $this->plan = $plan;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
