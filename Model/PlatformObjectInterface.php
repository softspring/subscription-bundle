<?php

namespace Softspring\SubscriptionBundle\Model;

interface PlatformObjectInterface
{
    /**
     * @return int|null
     */
    public function getPlatform(): ?int;

    /**
     * @return string|null
     */
    public function getPlatformId(): ?string;

    /**
     * @param int|null $platform
     */
    public function setPlatform(?int $platform): void;

    /**
     * @param string|null $platformId
     */
    public function setPlatformId(?string $platformId): void;

    /**
     * @return bool
     */
    public function isTestMode(): bool;

    /**
     * @param bool $testMode
     */
    public function setTestMode(bool $testMode): void;
}