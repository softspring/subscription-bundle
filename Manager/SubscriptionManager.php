<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\CrudlBundle\Manager\CrudlEntityManagerTrait;
use Softspring\CustomerBundle\Platform\ApiManagerInterface;
use Softspring\SubscriptionBundle\Model\CustomerHasTriedInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionItemInterface;
use Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Platform\Response\SubscriptionResponse;

class SubscriptionManager implements SubscriptionManagerInterface
{
    use CrudlEntityManagerTrait;

    /**
     * @var ApiManagerInterface
     */
    protected $api;

    /**
     * @var SubscriptionItemManagerInterface
     */
    protected $itemManager;

    /**
     * SubscriptionManager constructor.
     *
     * @param EntityManagerInterface           $em
     * @param ApiManagerInterface              $api
     * @param SubscriptionItemManagerInterface $itemManager
     */
    public function __construct(EntityManagerInterface $em, ApiManagerInterface $api, SubscriptionItemManagerInterface $itemManager)
    {
        $this->em = $em;
        $this->api = $api;
        $this->itemManager = $itemManager;
    }

    /**
     * @return SubscriptionItemManagerInterface
     */
    public function getItemManager(): SubscriptionItemManagerInterface
    {
        return $this->itemManager;
    }

    public function getTargetClass(): string
    {
        return SubscriptionInterface::class;
    }

    /**
     * @inheritDoc
     */
    public function subscribe(SubscriptionCustomerInterface $customer, PlanInterface $plan, int $quantity = 1): SubscriptionInterface
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $this->createEntity();
        $customer->addSubscription($subscription);
        $subscription->setPlatform($this->api->platformId());

        $subscription->addItem($item = $this->itemManager->createEntity());
        $item->setPlatform($this->api->platformId());
        $item->setPlan($plan);
        $item->setQuantity($quantity);

        $this->api->get('subscription')->create($subscription);

        $this->saveEntity($subscription);

        return $subscription;
    }

    public function addPlan(SubscriptionInterface $subscription, PlanInterface $plan, int $quantity = 1): SubscriptionItemInterface
    {
        $items = $subscription->getItemsForPlan($plan);

        if ($items->count()) {
            /** @var SubscriptionItemInterface $item */
            $item = $items->first();
            $item->setQuantity($item->getQuantity() + $quantity);
        } else {
            $subscription->addItem($item = $this->itemManager->createEntity());
            $item->setPlatform($this->api->platformId());
            $item->setPlan($plan);
            $item->setQuantity($quantity);
        }

        $this->api->get('subscription')->update($subscription);

        $this->saveEntity($subscription);

        return $item;
    }

    public function unsubscribe(SubscriptionItemInterface $item)
    {

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

        if ($customer instanceof CustomerHasTriedInterface) {
            $customer->setTried(true);
        }

        return $this->updateFromPlatform($subscription, $subscriptionResponse);
    }

    /**
     * @inheritDoc
     */
    public function cancelRenovation(SubscriptionInterface $subscription): void
    {
        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->cancelRenovation($subscription);
        $this->updateFromPlatform($subscription, $subscriptionResponse);
    }

    /**
     * @inheritDoc
     */
    public function uncancelRenovation(SubscriptionInterface $subscription): void
    {
        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->uncancelRenovation($subscription);
        $this->updateFromPlatform($subscription, $subscriptionResponse);
    }

    /**
     * @inheritDoc
     */
    public function sync(SubscriptionInterface $subscription): void
    {
        $this->api->get('subscription')->get($subscription);
    }

    /**
     * @inheritDoc
     */
    public function cancel(SubscriptionInterface $subscription): void
    {
        $subscriptionResponse = $this->api->get('subscription')->cancel($subscription);
        $this->updateFromPlatform($subscription, $subscriptionResponse);
    }

    /**
     * @inheritDoc
     */
    public function upgrade(SubscriptionInterface $subscription, PlanInterface $plan): void
    {
        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->upgrade($subscription, $plan);
        $subscription->setPlan($plan);
        $this->updateFromPlatform($subscription, $subscriptionResponse);
    }

    /**
     * @inheritDoc
     */
    public function finishTrial(SubscriptionInterface $subscription, PlanInterface $plan): void
    {
        if ($subscription->getStatus() != SubscriptionInterface::STATUS_TRIALING) {
            throw new SubscriptionException('Subscription is not trialing now');
        }

        /** @var SubscriptionResponse $subscriptionResponse */
        $subscriptionResponse = $this->api->get('subscription')->finishTrial($subscription, $plan);

        $this->updateFromPlatform($subscription, $subscriptionResponse);
    }

    /**
     * @inheritDoc
     * @deprecated
     */
    public function updateFromPlatform(SubscriptionInterface $subscription, SubscriptionResponse $subscriptionResponse): SubscriptionInterface
    {
        $subscription->setPlatformId($subscriptionResponse->getId());
        $subscription->setPlatformData($subscriptionResponse->getPlatformNativeArray());
        $subscription->setTestMode($subscriptionResponse->isTesting());

        $subscription->setStartDate($subscriptionResponse->getCurrentPeriodStart());
        $subscription->setEndDate($subscriptionResponse->getCurrentPeriodEnd());
        $subscription->setStatus($subscriptionResponse->getStatus());

        if ($subscriptionResponse->getCancelAt() instanceof \DateTime) {
            $subscription->setCancelScheduled($subscriptionResponse->getCancelAt());
        } else {
            $subscription->setCancelScheduled(null);
        }

        $this->saveEntity($subscription);

        return $subscription;
    }
}