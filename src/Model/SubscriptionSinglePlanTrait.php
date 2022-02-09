<?php

namespace Softspring\SubscriptionBundle\Model;

trait SubscriptionSinglePlanTrait
{
    /**
     * @var PlanInterface|null
     */
    protected $plan;

    public function getPlan(): ?PlanInterface
    {
        return $this->plan;
    }

    public function setPlan(?PlanInterface $plan): void
    {
        $this->plan = $plan;
    }
}
