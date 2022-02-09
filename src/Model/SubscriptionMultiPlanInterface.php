<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

interface SubscriptionMultiPlanInterface
{
    /**
     * @return SubscriptionItemInterface[]|Collection
     */
    public function getItems(): Collection;

    public function addItem(SubscriptionItemInterface $item): void;

    public function removeItem(SubscriptionItemInterface $item): void;

    public function getItemsForPlan(PlanInterface $plan): Collection;
}
