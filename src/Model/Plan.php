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
     *
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(?int $interval): void
    {
        $this->interval = $interval;
    }

    public function getIntervalCount(): ?int
    {
        return $this->intervalCount;
    }

    public function setIntervalCount(?int $intervalCount): void
    {
        $this->intervalCount = $intervalCount;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @deprecated
     */
    public function isOnline(): bool
    {
        return $this->online;
    }

    /**
     * @deprecated
     */
    public function setOnline(bool $online): void
    {
        $this->online = $online;
    }
}
