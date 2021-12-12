<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

trait SubscriptionMultiPlanTrait
{
    /**
     * @var SubscriptionItemInterface[]|Collection
     */
    protected $items;

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(SubscriptionItemInterface $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setSubscription($this);
        }
    }

    public function removeItem(SubscriptionItemInterface $item): void
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
        }
    }

    public function getItemsForPlan(PlanInterface $plan): Collection
    {
        return $this->items->filter(function(SubscriptionItemInterface $item) use ($plan) {
            return $item->getPlan() === $plan;
        });
    }
}