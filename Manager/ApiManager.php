<?php

namespace Softspring\SubscriptionBundle\Manager;

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
     * ApiManager constructor.
     * @param string $name
     * @param PlanAdapterInterface $plan
     * @param SubscriptionAdapterInterface $subscription
     */
    public function __construct(string $name, PlanAdapterInterface $plan, SubscriptionAdapterInterface $subscription)
    {
        $this->name = $name;
        $this->plan = $plan;
        $this->subscription = $subscription;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return PlanAdapterInterface
     */
    public function plan(): PlanAdapterInterface
    {
        return $this->plan;
    }

    /**
     * @return SubscriptionAdapterInterface
     */
    public function subscription(): SubscriptionAdapterInterface
    {
        return $this->subscription;
    }
}