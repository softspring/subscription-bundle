<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\SubscriptionBundle\Adapter\ClientAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\PlanAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;

class ApiManager implements ApiManagerInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var PlanAdapterInterface
     */
    protected $plan;

    /**
     * @var SubscriptionAdapterInterface
     */
    protected $subscription;

    /**
     * @var ClientAdapterInterface
     */
    protected $client;

    /**
     * ApiManager constructor.
     * @param string $name
     * @param PlanAdapterInterface $plan
     * @param SubscriptionAdapterInterface $subscription
     * @param ClientAdapterInterface $client
     */
    public function __construct(string $name, PlanAdapterInterface $plan, SubscriptionAdapterInterface $subscription, ClientAdapterInterface $client)
    {
        $this->name = $name;
        $this->plan = $plan;
        $this->subscription = $subscription;
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function client(): ClientAdapterInterface
    {
        return $this->client;
    }

    /**
     * @inheritDoc
     */
    public function plan(): PlanAdapterInterface
    {
        return $this->plan;
    }

    /**
     * @inheritDoc
     */
    public function subscription(): SubscriptionAdapterInterface
    {
        return $this->subscription;
    }
}