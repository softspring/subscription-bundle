<?php

namespace Softspring\SubscriptionBundle\Model;

/**
 * @deprecated
 */
interface SubscriptionHasProductInterface
{
    public function getProduct(): ?ProductInterface;

    public function setProduct(?ProductInterface $product): void;
}