<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\SubscriptionBundle\Adapter\PlanAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;

interface ApiManagerInterface
{
    /**
     * @return PlanAdapterInterface
     */
    public function plan(): PlanAdapterInterface;

    /**
     * @return SubscriptionAdapterInterface
     */
    public function subscription(): SubscriptionAdapterInterface;
}