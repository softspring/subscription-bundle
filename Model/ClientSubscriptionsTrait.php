<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait ClientSubscriptionsTrait
{
    /**
     * @var SubscriptionInterface[]|Collection
     * @ORM\OneToMany(targetEntity="Softspring\SubscriptionBundle\Model\SubscriptionInterface", mappedBy="client", cascade={"persist"})
     */
    protected $subscriptions;

    /**
     * @var bool
     * @ORM\Column(name="has_tried", type="boolean", nullable=false, options={"default"=false})
     */
    protected $tried = false;

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

    /**
     * @return bool
     */
    public function hasTried(): bool
    {
        return $this->tried;
    }

    /**
     * @param bool $tried
     */
    public function setTried(bool $tried): void
    {
        $this->tried = $tried;
    }
}