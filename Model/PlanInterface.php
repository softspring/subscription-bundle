<?php

namespace Softspring\SubscriptionBundle\Model;

interface PlanInterface extends PlatformObjectInterface
{
    const INTERVAL_DAY = 1;
    const INTERVAL_WEEK = 2;
    const INTERVAL_MONTH = 3;
    const INTERVAL_YEAR = 4;

    /**
     * @return string|null
     */
    public function getPlanKey(): ?string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @return float|null
     */
    public function getAmount(): ?float;

    /**
     * @return int|null
     */
    public function getInterval(): ?int;

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @return bool
     */
    public function isOnline(): bool;
}