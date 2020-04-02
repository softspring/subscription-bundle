<?php

namespace Softspring\SubscriptionBundle\Model;

use Softspring\CustomerBundle\Model\PlatformObjectInterface;

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

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * @param string|null $currency
     */
    public function setCurrency(?string $currency): void;

    /**
     * @param float|null $amount
     */
    public function setAmount(?float $amount): void;

    /**
     * @param int|null $interval
     */
    public function setInterval(?int $interval): void;

    /**
     * @param bool|null $active
     */
    public function setActive(?bool $active): void;

    /**
     * @param bool $online
     */
    public function setOnline(bool $online): void;

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface;

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void;
}