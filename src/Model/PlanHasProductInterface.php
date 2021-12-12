<?php

namespace Softspring\SubscriptionBundle\Model;

interface PlanHasProductInterface
{
    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface;

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void;
}