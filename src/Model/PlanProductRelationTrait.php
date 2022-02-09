<?php

namespace Softspring\SubscriptionBundle\Model;

trait PlanProductRelationTrait
{
    /**
     * @var ProductInterface|null
     */
    protected $product;

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }
}
