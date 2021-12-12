<?php

namespace Softspring\SubscriptionBundle\Model;

trait SubscriptionSinglePlanTrait
{
    /**
     * @var PlanInterface|null
     */
    protected $plan;

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
}