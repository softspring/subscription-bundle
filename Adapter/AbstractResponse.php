<?php

namespace Softspring\SubscriptionBundle\Adapter;

abstract class AbstractResponse
{
    /**
     * @var int
     */
    protected $platform;

    /**
     * @var bool
     */
    protected $testing;

    /**
     * @var mixed
     */
    protected $platformNativeResponse;

    /**
     * AbstractResponse constructor.
     *
     * @param int   $platform
     * @param mixed $platformResponse
     */
    public function __construct(int $platform, $platformResponse)
    {
        $this->platform = $platform;
        $this->platformNativeResponse = $platformResponse->toArray();
    }

    /**
     * @return int
     */
    public function getPlatform(): int
    {
        return $this->platform;
    }

    /**
     * @return mixed
     */
    public function getPlatformNativeResponse()
    {
        return $this->platformNativeResponse;
    }

    /**
     * @return array
     */
    public function getPlatformNativeArray(): array
    {
        if (is_array($this->platformNativeResponse)) {
            return $this->platformNativeResponse;
        }

        if (method_exists($this->platformNativeResponse, 'toArray')) {
            $this->platformNativeResponse->toArray();
        }

        throw new \Exception('Don\'t know how to convert platform object to array');
    }

    /**
     * @return bool
     */
    public function isTesting(): bool
    {
        return $this->testing;
    }
}