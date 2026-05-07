<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\GetResponseTrait;
use Softspring\PlatformBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\HttpFoundation\Request;

class UpgradeFailedGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    public function __construct(
        protected SubscriptionInterface $subscription,
        protected PlanInterface $plan,
        protected SubscriptionException $exception,
        ?Request $request,
    ) {
        parent::__construct($request);
    }

    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    public function getException(): SubscriptionException
    {
        return $this->exception;
    }
}
