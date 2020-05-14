<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;

class SubscriptionUpgradeEvent extends SubscriptionEvent
{
    /**
     * @var PlanInterface
     */
    protected $oldPlan;

    /**
     * @var PlanInterface
     */
    protected $newPlan;

    public function __construct(SubscriptionInterface $subscription, PlanInterface $oldPlan, PlanInterface $newPlan)
    {
        parent::__construct($subscription);
        $this->oldPlan = $oldPlan;
        $this->newPlan = $newPlan;
    }

    /**
     * @return PlanInterface
     */
    public function getOldPlan(): PlanInterface
    {
        return $this->oldPlan;
    }

    /**
     * @return PlanInterface
     */
    public function getNewPlan(): PlanInterface
    {
        return $this->newPlan;
    }
}