<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\AdminBundle\Manager\AdminEntityManagerTrait;
use Softspring\CustomerBundle\Manager\ApiManagerInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionResponse;
use Softspring\SubscriptionBundle\Model\CustomerHasTriedInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;

class SubscriptionManager implements SubscriptionManagerInterface
{
    use AdminEntityManagerTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ApiManagerInterface
     */
    protected $api;

    /**
     * SubscriptionManager constructor.
     * @param EntityManagerInterface $em
     * @param ApiManagerInterface $api
     */
    public function __construct(EntityManagerInterface $em, ApiManagerInterface $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    public function getTargetClass(): string
    {
        return SubscriptionInterface::class;
    }

    /**
     * @inheritDoc
     */
    public function subscribe(SubscriptionCustomerInterface $customer, PlanInterface $plan): SubscriptionInterface
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $this->createEntity();
        $subscription->setCustomer($customer);
        $subscription->setPlan($plan);
        $subscription->setPlatform($this->api->platformId());

        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->subscribe($customer, $plan, []);

        $subscription->setTestMode($subscriptionResponse->isTesting());
        $subscription->setPlatformId($subscriptionResponse->getId());
        $subscription->setPlatformData($subscriptionResponse->getPlatformNativeArray());
        $subscription->setStartDate($subscriptionResponse->getCurrentPeriodStart());
        $subscription->setEndDate($subscriptionResponse->getCurrentPeriodEnd());
        $subscription->setStatus($subscriptionResponse->getStatus());

        $this->saveEntity($subscription);

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function trial(SubscriptionCustomerInterface $customer, PlanInterface $plan, int $days): SubscriptionInterface
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $this->createEntity();
        $subscription->setCustomer($customer);
        $subscription->setPlan($plan);
        $subscription->setPlatform($this->api->platformId());

        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->trial($customer, $plan, $days, []);

        $subscription->setTestMode($subscriptionResponse->isTesting());
        $subscription->setPlatformId($subscriptionResponse->getId());
        $subscription->setPlatformData($subscriptionResponse->getPlatformNativeArray());
        $subscription->setStartDate($subscriptionResponse->getCurrentPeriodStart());
        $subscription->setEndDate($subscriptionResponse->getCurrentPeriodEnd());
        $subscription->setStatus($subscriptionResponse->getStatus());

        if ($customer instanceof CustomerHasTriedInterface) {
            $customer->setTried(true);
        }

        $this->saveEntity($subscription);

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function cancel(SubscriptionCustomerInterface $customer, SubscriptionInterface $subscription): void
    {
        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->cancel($subscription);
        $subscription->setCancelScheduled($subscriptionResponse->getCancelAt());
        $this->saveEntity($subscription);
    }

    /**
     * @inheritDoc
     */
    public function uncancel(SubscriptionCustomerInterface $customer, SubscriptionInterface $subscription): void
    {
        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->uncancel($subscription);
        $subscription->setCancelScheduled(null);
        $this->saveEntity($subscription);
    }

    /**
     * @inheritDoc
     */
    public function upgrade(SubscriptionCustomerInterface $customer, SubscriptionInterface $subscription, PlanInterface $plan): void
    {
        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->upgrade($subscription, $plan);
        $subscription->setPlan($plan);
        $this->saveEntity($subscription);
    }

    /**
     * @inheritDoc
     */
    public function finishTrial(SubscriptionCustomerInterface $customer, SubscriptionInterface $subscription, PlanInterface $plan): void
    {
        if ($subscription->getStatus() != SubscriptionInterface::STATUS_TRIALING) {
            throw new SubscriptionException('Subscription is not trialing now');
        }

        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->finishTrial($subscription, $plan);

        $subscription->setStartDate($subscriptionResponse->getCurrentPeriodStart());
        $subscription->setEndDate($subscriptionResponse->getCurrentPeriodEnd());
        $subscription->setStatus($subscriptionResponse->getStatus());

        $this->saveEntity($subscription);
    }
}