<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Model;

interface SubscriptionSinglePlanInterface
{
    public function getPlan(): ?PlanInterface;

    public function setPlan(?PlanInterface $plan): void;
}
