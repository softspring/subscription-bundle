<?php

namespace Softspring\SubscriptionBundle\Model;

use Softspring\PlatformBundle\Model\PlatformObjectInterface;

abstract class Plan implements PlanInterface
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var float|null
     */
    protected $amount;

    /**
     * @var int|null
     */
    protected $interval;

    /**
     * @var int|null
     */
    protected $intervalCount;

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var bool
     * @deprecated
     */
    protected $online = false;

    /**
     * @var ProductInterface|null
     */
    protected $product;

    public function getPlanKey(): ?string
    {
        return $this instanceof PlatformObjectInterface ? $this->getPlatformId() : null;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float|null $amount
     */
    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int|null
     */
    public function getInterval(): ?int
    {
        return $this->interval;
    }

    /**
     * @param int|null $interval
     */
    public function setInterval(?int $interval): void
    {
        $this->interval = $interval;
    }

    /**
     * @return int|null
     */
    public function getIntervalCount(): ?int
    {
        return $this->intervalCount;
    }

    /**
     * @param int|null $intervalCount
     */
    public function setIntervalCount(?int $intervalCount): void
    {
        $this->intervalCount = $intervalCount;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     */
    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return bool
     * @deprecated
     */
    public function isOnline(): bool
    {
        return $this->online;
    }

    /**
     * @param bool $online
     * @deprecated
     */
    public function setOnline(bool $online): void
    {
        $this->online = $online;
    }
}