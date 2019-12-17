<?php

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait PlatformObjectTrait
{
    /**
     * @var int|null
     * @ORM\Column(name="platform", type="smallint", nullable=true, options={"unsigned":true})
     */
    protected $platform;

    /**
     * @var string|null
     * @ORM\Column(name="platform_id", type="string", nullable=true)
     */
    protected $platformId;

    /**
     * @var array|null
     * @ORM\Column(name="platform_data", type="json", nullable=true)
     */
    protected $platformData;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="platform_last_sync", type="integer", nullable=true, options={"unsigned":true})
     */
    protected $platformLastSync;

    /**
     * @var bool
     * @ORM\Column(name="platform_conflict", type="boolean", nullable=false, options={"default":0})
     */
    protected $platformConflict = false;

    /**
     * @var bool
     * @ORM\Column(name="test_mode", type="boolean", nullable=false, options={"default":0})
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

    /**
     * @return array|null
     */
    public function getPlatformData(): ?array
    {
        return $this->platformData;
    }

    /**
     * @param array|null $platformData
     */
    public function setPlatformData(?array $platformData): void
    {
        $this->platformData = $platformData;
    }

    /**
     * @return \DateTime|null
     */
    public function getPlatformLastSync(): ?\DateTime
    {
        return $this->platformLastSync ? \DateTime::createFromFormat('U', $this->platformLastSync) : null;
    }

    /**
     * @param \DateTime|null $platformLastSync
     */
    public function setPlatformLastSync(?\DateTime $platformLastSync): void
    {
        $this->platformLastSync = $platformLastSync instanceof \DateTime ? $platformLastSync->format('U') : null;
    }

    /**
     * @return bool
     */
    public function isPlatformConflict(): bool
    {
        return $this->platformConflict;
    }

    /**
     * @param bool $platformConflict
     */
    public function setPlatformConflict(bool $platformConflict): void
    {
        $this->platformConflict = $platformConflict;
    }
}