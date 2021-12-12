<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

/**
 * Trait ClientSubscriptionsTrait
 */
trait CustomerSubscriptionsTrait
{
    /**
     * @var SubscriptionInterface[]|Collection
     */
    protected $subscriptions;

    /**
     * @return Collection|SubscriptionInterface[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    /**
     * @param SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription): void
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setCustomer($this);
        }
    }

    /**
     * @return Collection|SubscriptionInterface[]
     */
    public function getActiveSubscriptions(): Collection
    {
        return $this->getSubscriptions()->filter(function (SubscriptionInterface $subscription) {
            return in_array($subscription->getStatus(), [
                SubscriptionInterface::STATUS_ACTIVE,
                SubscriptionInterface::STATUS_TRIALING,
                SubscriptionInterface::STATUS_UNPAID,
                SubscriptionInterface::STATUS_CANCELED,
                SubscriptionInterface::STATUS_SUSPENDED,
            ]);
        });
    }
}