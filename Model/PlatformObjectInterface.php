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
     * @return array|null
     */
    public function getPlatformData(): ?array;

    /**
     * @param array|null $platformData
     */
    public function setPlatformData(?array $platformData): void;

    /**
     * @return \DateTime|null
     */
    public function getPlatformLastSync(): ?\DateTime;

    /**
     * @param \DateTime|null $platformLastSync
     */
    public function setPlatformLastSync(?\DateTime $platformLastSync): void;

    /**
     * @return bool
     */
    public function isTestMode(): bool;

    /**
     * @param bool $testMode
     */
    public function setTestMode(bool $testMode): void;

    /**
     * @return bool
     */
    public function isPlatformConflict(): bool;

    /**
     * @param bool $platformConflict
     */
    public function setPlatformConflict(bool $platformConflict): void;
}