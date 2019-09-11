<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\AdminBundle\Manager\AdminEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\ClientInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;

interface SubscriptionManagerInterface extends AdminEntityManagerInterface
{
    /**
     * @param ClientInterface $client
     * @param PlanInterface $plan
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function subscribe(ClientInterface $client, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param ClientInterface $client
     * @param PlanInterface $plan
     * @return SubscriptionInterface
     * @throws SubscriptionException
     */
    public function trial(ClientInterface $client, PlanInterface $plan): SubscriptionInterface;

    /**
     * @param ClientInterface $client
     * @param SubscriptionInterface $subscription
     * @throws SubscriptionException
     */
    public function cancel(ClientInterface $client, SubscriptionInterface $subscription): void;

    /**
     * @param ClientInterface $client
     * @param SubscriptionInterface $subscription
     * @throws SubscriptionException
     */
    public function uncancel(ClientInterface $client, SubscriptionInterface $subscription): void;

    /**
     * @param ClientInterface $client
     * @param SubscriptionInterface $subscription
     * @param PlanInterface $plan
     * @throws SubscriptionException
     */
    public function upgrade(ClientInterface $client, SubscriptionInterface $subscription, PlanInterface $plan): void;
}