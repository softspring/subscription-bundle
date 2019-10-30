<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\SubscriptionBundle\Adapter\CustomerAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\PlanAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;
use Softspring\SubscriptionBundle\PlatformInterface;

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
     * @var CustomerAdapterInterface
     */
    protected $customer;

    /**
     * ApiManager constructor.
     *
     * @param string                       $name
     * @param PlanAdapterInterface         $plan
     * @param SubscriptionAdapterInterface $subscription
     * @param CustomerAdapterInterface     $customer
     */
    public function __construct(string $name, PlanAdapterInterface $plan, SubscriptionAdapterInterface $subscription, CustomerAdapterInterface $customer)
    {
        $this->name = $name;
        $this->plan = $plan;
        $this->subscription = $subscription;
        $this->customer = $customer;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name;
    }

    public function platformId(): int
    {
        switch ($this->name) {
            case 'stripe':
                return PlatformInterface::PLATFORM_STRIPE;
        }

        throw new \Exception('Not valid or implemented platform');
    }

    /**
     * @inheritDoc
     */
    public function customer(): CustomerAdapterInterface
    {
        return $this->customer;
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