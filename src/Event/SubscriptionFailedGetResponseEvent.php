<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\PlatformBundle\Exception\SubscriptionException;
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
     *
     * @param SubscriptionCustomerInterface $client
     * @param PlanInterface                 $plan
     * @param SubscriptionException         $exception
     * @param Request|null                  $request
     */
    public function __construct(SubscriptionCustomerInterface $client, PlanInterface $plan, SubscriptionException $exception, ?Request $request)
    {
        parent::__construct($request);
        $this->client = $client;
        $this->plan = $plan;
        $this->exception = $exception;
    }

    /**
     * @return SubscriptionCustomerInterface
     */
    public function getClient(): SubscriptionCustomerInterface
    {
        return $this->client;
    }

    /**
     * @return PlanInterface
     */
    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    /**
     * @return SubscriptionException
     */
    public function getException(): SubscriptionException
    {
        return $this->exception;
    }
}