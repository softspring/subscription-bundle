<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

trait ProductPlansRelationTrait
{
    /**
     * @var Collection|PlanInterface[]
     */
    protected $plans;

    /**
     * @return Collection|PlanInterface[]
     */
    public function getPlans(): Collection
    {
        return $this->plans;
    }

    /**
     * @param PlanInterface $plan
     */
    public function addPlan(PlanInterface $plan): void
    {
        if (!$this->plans->contains($plan)) {
            $this->plans->add($plan);
        }

        if ($plan instanceof PlanHasProductInterface) {
            $plan->setProduct($this);
        }
    }

    /**
     * @param PlanInterface $plan
     */
    public function removePlan(PlanInterface $plan): void
    {
        if ($this->plans->contains($plan)) {
            $this->plans->removeElement($plan);
        }
    }
}