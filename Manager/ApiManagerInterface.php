<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\SubscriptionBundle\Adapter\ClientAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\PlanAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;

interface ApiManagerInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return ClientAdapterInterface
     */
    public function client(): ClientAdapterInterface;

    /**
     * @return PlanAdapterInterface
     */
    public function plan(): PlanAdapterInterface;

    /**
     * @return SubscriptionAdapterInterface
     */
    public function subscription(): SubscriptionAdapterInterface;
}