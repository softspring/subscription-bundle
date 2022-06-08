<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\RequestEvent;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Symfony\Component\HttpFoundation\Request;

class PlanEvent extends RequestEvent
{
    /**
     * @var PlanInterface
     */
    protected $plan;

    /**
     * PlanEvent constructor.
     */
    public function __construct(PlanInterface $plan, ?Request $request)
    {
        parent::__construct($request);
        $this->plan = $plan;
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }
}
