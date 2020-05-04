<?php

namespace Softspring\SubscriptionBundle\Model;

interface SubscriptionMultiPlanInterface
{
    /**
     * @return SubscriptionItemInterface[]
     */
    public function getItems(): array;
}