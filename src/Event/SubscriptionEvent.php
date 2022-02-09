<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Contracts\EventDispatcher\Event as EventContract;

/**
 * Class Event.
 */
class SubscriptionEvent extends EventContract
{
    /**
     * @var SubscriptionInterface
     */
    protected $subscription;

    /**
     * SubscriptionEvent constructor.
     */
    public function __construct(SubscriptionInterface $subscription)
    {
        $this->subscription = $subscription;
    }

    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }
}
