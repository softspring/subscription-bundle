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
     *
     * @param SubscriptionInterface $subscription
     * @param PlanInterface         $oldPlan
     * @param PlanInterface         $newPlan
     * @param Request|null          $request
     */
    public function __construct(SubscriptionInterface $subscription, PlanInterface $oldPlan, PlanInterface $newPlan, ?Request $request)
    {
        parent::__construct($request);
        $this->subscription = $subscription;
        $this->oldPlan = $oldPlan;
        $this->newPlan = $newPlan;
    }

    /**
     * @return SubscriptionInterface
     */
    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }

    /**
     * @return PlanInterface
     */
    public function getNewPlan(): PlanInterface
    {
        return $this->newPlan;
    }

    /**
     * @return PlanInterface
     */
    public function getOldPlan(): PlanInterface
    {
        return $this->oldPlan;
    }
}