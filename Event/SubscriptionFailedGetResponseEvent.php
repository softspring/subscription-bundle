<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\CustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionFailedGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var CustomerInterface
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
     * @param CustomerInterface     $client
     * @param PlanInterface         $plan
     * @param SubscriptionException $exception
     * @param Request|null          $request
     */
    public function __construct(CustomerInterface $client, PlanInterface $plan, SubscriptionException $exception, ?Request $request)
    {
        parent::__construct($request);
        $this->client = $client;
        $this->plan = $plan;
        $this->exception = $exception;
    }

    /**
     * @return CustomerInterface
     */
    public function getClient(): CustomerInterface
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