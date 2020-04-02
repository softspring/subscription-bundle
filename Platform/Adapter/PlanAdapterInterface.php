<?php

namespace Softspring\SubscriptionBundle\Platform\Adapter;

use Softspring\CustomerBundle\Platform\Adapter\PlatformAdapterInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Platform\Response\PlanListResponse;

interface PlanAdapterInterface extends PlatformAdapterInterface
{
    /**
     * @param PlanInterface $plan
     *
     * @return mixed
     */
    public function create(PlanInterface $plan);

    /**
     * @param PlanInterface $plan
     *
     * @return mixed
     */
    public function update(PlanInterface $plan);

    /**
     * @param PlanInterface $plan
     *
     * @return mixed
     */
    public function delete(PlanInterface $plan);

    /**
     * @return PlanListResponse
     */
    public function list(): PlanListResponse;
}