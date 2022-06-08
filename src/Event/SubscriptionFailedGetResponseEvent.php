<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\GetResponseTrait;
use Softspring\PlatformBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionFailedGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var SubscriptionCustomerInterface
     */
    protected $client;

    /**
     * @var PlanInterface
     */
    protected $plan;

    /**
     * @var SubscriptionException
     */
    protected $exception;

    /**
     * SubscriptionFailedGetResponseEvent constructor.
     */
    public function __construct(SubscriptionCustomerInterface $client, PlanInterface $plan, SubscriptionException $exception, ?Request $request)
    {
        parent::__construct($request);
        $this->client = $client;
        $this->plan = $plan;
        $this->exception = $exception;
    }

    public function getClient(): SubscriptionCustomerInterface
    {
        return $this->client;
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    public function getException(): SubscriptionException
    {
        return $this->exception;
    }
}
