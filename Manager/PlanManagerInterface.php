<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\AdminBundle\Manager\AdminEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;

interface PlanManagerInterface extends AdminEntityManagerInterface
{
    /**
     * @param string $plan
     * @return PlanInterface|null
     */
    public function convert(string $plan): ?PlanInterface;

//    /**
//     * @param PlanInterface $plan
//     */
//    public function syncFromPlatform(PlanInterface $plan): void;
//
//    /**
//     * @param PlanInterface $plan
//     */
//    public function syncToPlatform(PlanInterface $plan): void;

    /**
     *
     */
    public function syncAll(): void;
}