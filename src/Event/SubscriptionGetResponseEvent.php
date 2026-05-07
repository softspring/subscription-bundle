<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    public function __construct(protected SubscriptionInterface $subscription, Request $request)
    {
        parent::__construct($request);
    }

    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }
}
