<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\ExtraBundle\Event\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var SubscriptionInterface
     */
    protected $subscription;

    /**
     * SubscriptionGetResponseEvent constructor.
     * @param SubscriptionInterface $subscription
     * @param Request $request
     */
    public function __construct(SubscriptionInterface $subscription, Request $request)
    {
        parent::__construct($request);
        $this->subscription = $subscription;
    }

    /**
     * @return SubscriptionInterface
     */
    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }
}