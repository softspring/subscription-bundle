<?php

namespace Softspring\SubscriptionBundle\Model;

trait PlatformObjectTrait
{
    /**
     * @var int|null
     */
    protected $platform;

    /**
     * @var string|null
     */
    protected $platformId;

    /**
     * @var bool
     */
    protected $testMode = false;

    /**
     * @return int|null
     */
    public function getPlatform(): ?int
    {
        return $this->platform;
    }

    /**
     * @param int|null $platform
     */
    public function setPlatform(?int $platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @return string|null
     */
    public function getPlatformId(): ?string
    {
        return $this->platformId;
    }

    /**
     * @param string|null $platformId
     */
    public function setPlatformId(?string $platformId): void
    {
        $this->platformId = $platformId;
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * @param bool $testMode
     */
    public function setTestMode(bool $testMode): void
    {
        $this->testMode = $testMode;
    }
}