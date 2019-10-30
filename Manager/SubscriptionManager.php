<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\AdminBundle\Manager\AdminEntityManagerTrait;
use Softspring\SubscriptionBundle\Model\CustomerHasTriedInterface;
use Softspring\SubscriptionBundle\Model\CustomerInterface;
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
    public function subscribe(CustomerInterface $customer, PlanInterface $plan): SubscriptionInterface
    {
        $subscription = $this->createEntity();

        $this->api->subscription()->subscribe($subscription, $customer, $plan);

        $this->saveEntity($subscription);

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function trial(CustomerInterface $customer, PlanInterface $plan): SubscriptionInterface
    {
        $subscription = $this->createEntity();

        $this->api->subscription()->trial($subscription, $customer, $plan);

        if ($customer instanceof CustomerHasTriedInterface) {
            $customer->setTried(true);
        }

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function cancel(CustomerInterface $customer, SubscriptionInterface $subscription): void
    {
        $this->api->subscription()->cancel($subscription);
        $this->saveEntity($subscription);
    }

    /**
     * @inheritDoc
     */
    public function uncancel(CustomerInterface $customer, SubscriptionInterface $subscription): void
    {
        $this->api->subscription()->uncancel($subscription);
        $this->saveEntity($subscription);
    }


    /**
     * @param CustomerInterface     $customer
     * @param SubscriptionInterface $subscription
     * @param PlanInterface         $plan
     *
     * @throws SubscriptionException
     */
    public function upgrade(CustomerInterface $customer, SubscriptionInterface $subscription, PlanInterface $plan): void
    {
        $this->api->subscription()->upgrade($subscription, $plan);
        $this->saveEntity($subscription);
    }

    /**
     * @param CustomerInterface     $customer
     * @param SubscriptionInterface $subscription
     * @param PlanInterface         $plan
     *
     * @throws SubscriptionException
     */
    public function finishTrial(CustomerInterface $customer, SubscriptionInterface $subscription, PlanInterface $plan): void
    {
        $this->api->subscription()->finishTrial($subscription, $plan);
        $this->saveEntity($subscription);
    }
}