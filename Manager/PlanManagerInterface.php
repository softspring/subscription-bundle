<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\AdminBundle\Manager\AdminEntityManagerInterface;
use Softspring\Subscription\Model\PlanInterface;

interface PlanManagerInterface extends AdminEntityManagerInterface
{
    /**
     * @param string $plan
     * @return PlanInterface|null
     */
    public function convert(string $plan): ?PlanInterface;
}