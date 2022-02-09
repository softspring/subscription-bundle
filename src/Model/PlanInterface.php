<?php

namespace Softspring\SubscriptionBundle\Model;

interface PlanInterface
{
    public const INTERVAL_DAY = 1;
    public const INTERVAL_WEEK = 2;
    public const INTERVAL_MONTH = 3;
    public const INTERVAL_YEAR = 4;

    /**
     * @deprecated
     */
    public function getPlanKey(): ?string;

    public function getName(): ?string;

    public function getCurrency(): ?string;

    public function getAmount(): ?float;

    public function getInterval(): ?int;

    public function getIntervalCount(): ?int;

    public function isActive(): bool;

    /**
     * @deprecated
     */
    public function isOnline(): bool;

    public function setName(?string $name): void;

    public function setCurrency(?string $currency): void;

    public function setAmount(?float $amount): void;

    public function setInterval(?int $interval): void;

    public function setIntervalCount(?int $intervalCount): void;

    public function setActive(?bool $active): void;

    /**
     * @deprecated
     */
    public function setOnline(bool $online): void;
}
