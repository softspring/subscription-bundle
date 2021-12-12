<?php

namespace Softspring\SubscriptionBundle\Model;

trait PlanProductRelationTrait
{
    /**
     * @var ProductInterface|null
     */
    protected $product;

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }
}