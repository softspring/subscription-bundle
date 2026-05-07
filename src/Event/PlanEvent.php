<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\RequestEvent;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Symfony\Component\HttpFoundation\Request;

class PlanEvent extends RequestEvent
{
    public function __construct(protected PlanInterface $plan, ?Request $request)
    {
        parent::__construct($request);
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }
}
