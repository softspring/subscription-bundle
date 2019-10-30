<?php

namespace Softspring\SubscriptionBundle\Model;

interface PlanHasProductInterface
{
    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface;
}