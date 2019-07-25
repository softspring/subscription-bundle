<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\Subscription\Model\PlanInterface;

interface PlanAdapterInterface
{
    /**
     * @param PlanInterface $plan
     */
    public function create(PlanInterface $plan): void;

    /**
     * @param PlanInterface $plan
     */
    public function update(PlanInterface $plan): void;

    /**
     * @param PlanInterface $plan
     */
    public function delete(PlanInterface $plan): void;
}