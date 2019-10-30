<?php

namespace Softspring\SubscriptionBundle\Model;

interface SubscriptionHasProductInterface
{
    public function getProduct(): ?ProductInterface;

    public function setProduct(?ProductInterface $product): void;
}