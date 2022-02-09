<?php

namespace Softspring\SubscriptionBundle\Model;

interface SubscriptionTransitionInterface
{
    public function setSubscription(?SubscriptionInterface $subscription): void;

    public function getSubscription(): ?SubscriptionInterface;

    public function setDate(?\DateTime $date): void;

    public function getDate(): ?\DateTime;

    public function setStatus(?int $status): void;

    public function getStatus(): ?int;

    public function getStatusString(): ?string;
}
