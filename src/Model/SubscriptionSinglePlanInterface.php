<?php

namespace Softspring\SubscriptionBundle\Model;

interface SubscriptionSinglePlanInterface
{
    /**
     * @return PlanInterface|null
     */
    public function getPlan(): ?PlanInterface;

    /**
     * @param PlanInterface|null $plan
     */
    public function setPlan(?PlanInterface $plan): void;
}