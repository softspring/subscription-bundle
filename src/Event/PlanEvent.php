<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\RequestEvent;
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
     * @param PlanInterface $plan
     * @param Request|null $request
     */
    public function __construct(PlanInterface $plan, ?Request $request)
    {
        parent::__construct($request);
        $this->plan = $plan;
    }

    /**
     * @return PlanInterface
     */
    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }
}