<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\HttpFoundation\Request;

class UpgradeGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    public function __construct(
        protected SubscriptionInterface $subscription,
        protected PlanInterface $oldPlan,
        protected PlanInterface $newPlan,
        ?Request $request,
    ) {
        parent::__construct($request);
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
