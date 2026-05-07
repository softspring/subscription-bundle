<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;

class SubscriptionUpgradeEvent extends SubscriptionEvent
{
    protected PlanInterface $oldPlan;

    protected PlanInterface $newPlan;

    public function __construct(SubscriptionInterface $subscription, PlanInterface $oldPlan, PlanInterface $newPlan)
    {
        parent::__construct($subscription);
        $this->oldPlan = $oldPlan;
        $this->newPlan = $newPlan;
    }

    public function getOldPlan(): PlanInterface
    {
        return $this->oldPlan;
    }

    public function getNewPlan(): PlanInterface
    {
        return $this->newPlan;
    }
}
