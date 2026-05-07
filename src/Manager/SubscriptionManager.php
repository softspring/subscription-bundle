<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerTrait;
use Softspring\SubscriptionBundle\Event\SubscriptionEvent;
use Softspring\SubscriptionBundle\Event\SubscriptionUpgradeEvent;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionItemInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionMultiPlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionSinglePlanInterface;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    use CrudlEntityManagerTrait;
    public $api;

    public function __construct(
        protected EntityManagerInterface $em,
        protected SubscriptionItemManagerInterface $itemManager,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function getItemManager(): SubscriptionItemManagerInterface
    {
        return $this->itemManager;
    }

    public function getTargetClass(): string
    {
        return SubscriptionInterface::class;
    }

    public function subscribe(SubscriptionCustomerInterface $customer, PlanInterface $plan, int $quantity = 1): SubscriptionInterface
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $this->createEntity();
        $customer->addSubscription($subscription);

        if ($subscription instanceof SubscriptionSinglePlanInterface) {
            $subscription->setPlan($plan);
        } elseif ($subscription instanceof SubscriptionMultiPlanInterface) {
            $subscription->addItem($item = $this->itemManager->createEntity());
            $item->setPlan($plan);
            $item->setQuantity($quantity);
        }

        $this->eventDispatcher->dispatch(new SubscriptionEvent($subscription), SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE);

        $this->saveEntity($subscription);

        return $subscription;
    }

    public function addPlan(SubscriptionInterface $subscription, PlanInterface $plan, int $quantity = 1): SubscriptionItemInterface
    {
        if (!$subscription instanceof SubscriptionMultiPlanInterface) {
            throw new SubscriptionException('Can not add a plan to a single plan subscription');
        }

        $items = $subscription->getItemsForPlan($plan);

        if ($items->count()) {
            /** @var SubscriptionItemInterface $item */
            $item = $items->first();
            $item->setQuantity($item->getQuantity() + $quantity);
        } else {
            $subscription->addItem($item = $this->itemManager->createEntity());
            $item->setPlan($plan);
            $item->setQuantity($quantity);
        }

        $this->eventDispatcher->dispatch(new SubscriptionEvent($subscription), SfsSubscriptionEvents::SUBSCRIPTION_ADD_PLAN);

        $this->saveEntity($subscription);

        return $item;
    }

    public function removePlan(SubscriptionInterface $subscription, PlanInterface $plan, int $quantity = 1): SubscriptionInterface
    {
        $this->eventDispatcher->dispatch(new SubscriptionEvent($subscription), SfsSubscriptionEvents::SUBSCRIPTION_UNSUBSCRIBE);

        $this->saveEntity($subscription);

        return $subscription;
    }

    public function trial(SubscriptionCustomerInterface $customer, PlanInterface $plan, int $days): SubscriptionInterface
    {
        // TODO REVIEW THIS
    }

    public function cancelRenovation(SubscriptionInterface $subscription): SubscriptionInterface
    {
        $this->eventDispatcher->dispatch(new SubscriptionEvent($subscription), SfsSubscriptionEvents::SUBSCRIPTION_CANCEL_RENOVATION);

        $this->saveEntity($subscription);

        return $subscription;
    }

    public function uncancelRenovation(SubscriptionInterface $subscription): SubscriptionInterface
    {
        $this->eventDispatcher->dispatch(new SubscriptionEvent($subscription), SfsSubscriptionEvents::SUBSCRIPTION_UNCANCEL_RENOVATION);

        $this->saveEntity($subscription);

        return $subscription;
    }

    public function cancel(SubscriptionInterface $subscription): SubscriptionInterface
    {
        $this->eventDispatcher->dispatch(new SubscriptionEvent($subscription), SfsSubscriptionEvents::SUBSCRIPTION_CANCEL);

        $this->saveEntity($subscription);

        return $subscription;
    }

    public function sync(SubscriptionInterface $subscription): SubscriptionInterface
    {
        $this->eventDispatcher->dispatch(new SubscriptionEvent($subscription), SfsSubscriptionEvents::SUBSCRIPTION_SYNC);

        $this->saveEntity($subscription);

        return $subscription;
    }

    public function upgrade(SubscriptionInterface $subscription, PlanInterface $plan): SubscriptionInterface
    {
        if ($subscription instanceof SubscriptionMultiPlanInterface) {
            // TODO IMPLEMENT THIS
        } elseif ($subscription instanceof SubscriptionSinglePlanInterface) {
            $oldPlan = $subscription->getPlan();
            $subscription->setPlan($plan);
            $this->eventDispatcher->dispatch(new SubscriptionUpgradeEvent($subscription, $oldPlan, $plan), SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE);
        }

        $this->saveEntity($subscription);

        return $subscription;
    }

    public function finishTrial(SubscriptionInterface $subscription, PlanInterface $plan): SubscriptionInterface
    {
        if (SubscriptionInterface::STATUS_TRIALING != $subscription->getStatus()) {
            throw new SubscriptionException('Subscription is not trialing now');
        }

        $this->api->get('subscription')->finishTrial($subscription, $plan);
    }
}
