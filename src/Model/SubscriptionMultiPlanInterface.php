<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

interface SubscriptionMultiPlanInterface
{
    /**
     * @return SubscriptionItemInterface[]|Collection
     */
    public function getItems(): Collection;

    /**
     * @param SubscriptionItemInterface $item
     */
    public function addItem(SubscriptionItemInterface $item): void;

    /**
     * @param SubscriptionItemInterface $item
     */
    public function removeItem(SubscriptionItemInterface $item): void;

    /**
     * @param PlanInterface $plan
     *
     * @return Collection
     */
    public function getItemsForPlan(PlanInterface $plan): Collection;
}