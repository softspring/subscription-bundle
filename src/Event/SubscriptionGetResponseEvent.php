<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\GetResponseTrait;
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
     */
    public function __construct(SubscriptionInterface $subscription, Request $request)
    {
        parent::__construct($request);
        $this->subscription = $subscription;
    }

    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }
}
