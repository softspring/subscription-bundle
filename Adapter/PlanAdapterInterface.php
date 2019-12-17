<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\CustomerBundle\Adapter\PlatformAdapterInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;

interface PlanAdapterInterface extends PlatformAdapterInterface
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

    /**
     * @return PlanListResponse
     */
    public function list(): PlanListResponse;
}