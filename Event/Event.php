<?php

namespace Softspring\SubscriptionBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event as EventContract;

/**
 * Class Event
 *
 * @deprecated Use Softspring\CoreBundle\Event\RequestEvent instead of this
 */
class Event extends EventContract
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