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
}