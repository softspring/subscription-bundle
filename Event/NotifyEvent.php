<?php

namespace Softspring\SubscriptionBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class NotifyEvent extends Event
{
    /**
     * @var int
     */
    protected $platform;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * NotifyEvent constructor.
     * @param int $platform
     * @param string $name
     * @param mixed $data
     */
    public function __construct(int $platform, string $name, $data)
    {
        $this->platform = $platform;
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getPlatform(): int
    {
        return $this->platform;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}