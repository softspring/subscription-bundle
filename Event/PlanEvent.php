<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\SubscriptionBundle\Model\PlanInterface;
use Symfony\Component\HttpFoundation\Request;

class PlanEvent
{
    /**
     * @var PlanInterface
     */
    protected $plan;

    /**
     * @var Request|null
     */
    protected $request;

    /**
     * PlanEvent constructor.
     * @param PlanInterface $plan
     * @param Request|null $request
     */
    public function __construct(PlanInterface $plan, ?Request $request)
    {
        $this->plan = $plan;
        $this->request = $request;
    }

    /**
     * @return PlanInterface
     */
    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }
}