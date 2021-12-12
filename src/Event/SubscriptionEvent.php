<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event as EventContract;

/**
 * Class Event
 */
class SubscriptionEvent extends EventContract
{
    /**
     * @var SubscriptionInterface
     */
    protected $subscription;

    /**
     * SubscriptionEvent constructor.
     *
     * @param SubscriptionInterface $subscription
     */
    public function __construct(SubscriptionInterface $subscription)
    {
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