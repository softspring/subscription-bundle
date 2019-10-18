<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
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
     *
     * @param SubscriptionInterface $subscription
     * @param PlanInterface         $plan
     * @param SubscriptionException $exception
     * @param Request|null          $request
     */
    public function __construct(SubscriptionInterface $subscription, PlanInterface $plan, SubscriptionException $exception, ?Request $request)
    {
        parent::__construct($request);
        $this->subscription = $subscription;
        $this->plan = $plan;
        $this->exception = $exception;
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
    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    /**
     * @return SubscriptionException
     */
    public function getException(): SubscriptionException
    {
        return $this->exception;
    }
}