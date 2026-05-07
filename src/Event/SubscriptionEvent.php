<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Contracts\EventDispatcher\Event as EventContract;

class SubscriptionEvent extends EventContract
{
    public function __construct(protected SubscriptionInterface $subscription)
    {
    }

    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }
}
