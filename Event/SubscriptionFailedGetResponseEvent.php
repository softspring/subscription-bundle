<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\ExtraBundle\Event\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\ClientInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionFailedGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var ClientInterface
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
     * @var Request|null
     */
    protected $request;

    /**
     * SubscriptionFailedGetResponseEvent constructor.
     * @param ClientInterface $client
     * @param PlanInterface $plan
     * @param SubscriptionException $exception
     * @param Request|null $request
     */
    public function __construct(ClientInterface $client, PlanInterface $plan, SubscriptionException $exception, ?Request $request)
    {
        parent::__construct($request);
        $this->client = $client;
        $this->plan = $plan;
        $this->exception = $exception;
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
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