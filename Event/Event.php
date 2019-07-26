<?php

namespace Softspring\SubscriptionBundle\Event;

use Symfony\Component\HttpFoundation\Request;

class Event
{
    /**
     * @var Request|null
     */
    protected $request;

    /**
     * PlanEvent constructor.
     * @param Request|null $request
     */
    public function __construct(?Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }
}