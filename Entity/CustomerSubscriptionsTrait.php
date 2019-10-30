<?php

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;

trait CustomerSubscriptionsTrait
{
    /**
     * @var SubscriptionInterface[]|Collection
     * @ORM\OneToMany(targetEntity="Softspring\SubscriptionBundle\Model\SubscriptionInterface", mappedBy="customer", cascade={"persist"})
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
        }
    }

    /**
     * @return Collection|SubscriptionInterface[]
     */
    public function getActiveSubscriptions(): Collection
    {
        return $this->getSubscriptions()->filter(function (SubscriptionInterface $subscription) {
            return in_array($subscription->getStatus(), [SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::STATUS_TRIALING]);
        });
    }
}