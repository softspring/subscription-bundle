<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Model;

interface PlanHasProductInterface
{
    public function getProduct(): ?ProductInterface;

    public function setProduct(?ProductInterface $product): void;
}
