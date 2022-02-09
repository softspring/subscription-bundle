<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\GetResponseTrait;
use Softspring\PlatformBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\HttpFoundation\Request;

class UpgradeFailedGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var SubscriptionInterface
     */
    protected $subscription;

    /**
     * @var PlanInterface
     */
    protected $plan;

    /**
     * @var SubscriptionException
     */
    protected $exception;

    /**
     * UpgradeFailedGetResponseEvent constructor.
     */
    public function __construct(SubscriptionInterface $subscription, PlanInterface $plan, SubscriptionException $exception, ?Request $request)
    {
        parent::__construct($request);
        $this->subscription = $subscription;
        $this->plan = $plan;
        $this->exception = $exception;
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
