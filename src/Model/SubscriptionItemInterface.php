<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Model;

interface SubscriptionItemInterface
{
    public function getSubscription(): ?SubscriptionInterface;

    public function setSubscription(?SubscriptionInterface $subscription): void;

    public function getPlan(): ?PlanInterface;

    public function setPlan(?PlanInterface $plan): void;

    public function getQuantity(): int;

    public function setQuantity(int $quantity): void;
}
