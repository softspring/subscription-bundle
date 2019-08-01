<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\ExtraBundle\Event\GetResponseTrait;
use Softspring\Subscription\Model\SubscriptionInterface;

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
     */
    public function __construct(SubscriptionInterface $subscription)
    {
        parent::__construct(null);
        $this->subscription = $subscription;
    }
}