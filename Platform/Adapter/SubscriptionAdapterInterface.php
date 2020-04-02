<?php

namespace Softspring\SubscriptionBundle\Platform\Adapter;

use Softspring\CustomerBundle\Platform\Adapter\PlatformAdapterInterface;
use Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Platform\Response\SubscriptionResponse;

interface SubscriptionAdapterInterface extends PlatformAdapterInterface
{
    /**
     * @param mixed $customer
     * @param mixed $plan
     * @param array $options
     *
     * @return SubscriptionResponse
     *
     * @throws SubscriptionException
     */
    public function subscribe($customer, $plan, array $options = []): SubscriptionResponse;

    /**
     * @param mixed $customer
     * @param mixed $plan
     * @param int   $days
     * @param array $options
     *
     * @return SubscriptionResponse
     *
     * @throws SubscriptionException
     */
    public function trial($customer, $plan, int $days, array $options = []): SubscriptionResponse;

    /**
     * @param mixed $subscription
     *
     * @return SubscriptionResponse
     *
     * @throws SubscriptionException
     */
    public function details($subscription): SubscriptionResponse;

    /**
     * @param mixed $subscription
     *
     * @return SubscriptionResponse
     *
     * @throws SubscriptionException
     */
    public function cancel($subscription): SubscriptionResponse;

    /**
     * @param mixed $subscription
     *
     * @return SubscriptionResponse
     *
     * @throws SubscriptionException
     */
    public function uncancel($subscription): SubscriptionResponse;

    /**
     * @param       $subscription
     * @param       $plan
     * @param array $options
     *
     * @return SubscriptionResponse
     *
     * @throws SubscriptionException
     */
    public function upgrade($subscription, $plan, array $options = []): SubscriptionResponse;

    /**
     * @param mixed $subscription
     * @param mixed $plan
     *
     * @return SubscriptionResponse
     *
     * @throws SubscriptionException
     */
    public function finishTrial($subscription, $plan): SubscriptionResponse;
}