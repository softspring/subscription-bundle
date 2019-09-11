<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait ProductPlansRelationTrait
{
    /**
     * @var Collection|PlanInterface[]
     * @ORM\OneToMany(targetEntity="Softspring\SubscriptionBundle\Model\PlanInterface", mappedBy="product")
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