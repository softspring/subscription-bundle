<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\SubscriptionBundle\Adapter\CustomerAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\PlanAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\SourceAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;

interface ApiManagerInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return int
     */
    public function platformId(): int;

    /**
     * @return CustomerAdapterInterface
     */
    public function customer(): CustomerAdapterInterface;

    /**
     * @return PlanAdapterInterface
     */
    public function plan(): PlanAdapterInterface;

    /**
     * @return SubscriptionAdapterInterface
     */
    public function subscription(): SubscriptionAdapterInterface;

    /**
     * @return SourceAdapterInterface
     */
    public function source(): SourceAdapterInterface;
}