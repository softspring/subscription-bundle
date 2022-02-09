<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\HttpFoundation\Request;

class UpgradeGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var SubscriptionInterface
     */
    protected $subscription;

    /**
     * @var PlanInterface
     */
    protected $oldPlan;

    /**
     * @var PlanInterface
     */
    protected $newPlan;

    /**
     * UpgradeGetResponseEvent constructor.
     */
    public function __construct(SubscriptionInterface $subscription, PlanInterface $oldPlan, PlanInterface $newPlan, ?Request $request)
    {
        parent::__construct($request);
        $this->subscription = $subscription;
        $this->oldPlan = $oldPlan;
        $this->newPlan = $newPlan;
    }

    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }

    public function getNewPlan(): PlanInterface
    {
        return $this->newPlan;
    }

    public function getOldPlan(): PlanInterface
    {
        return $this->oldPlan;
    }
}
