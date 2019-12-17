<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\SubscriptionBundle\Exception\PlatformNotYetImplemented;
use Softspring\SubscriptionBundle\PlatformInterface;

abstract class AbstractListResponse extends AbstractResponse implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var array|AbstractResponse[]
     */
    protected $elements = [];

    /**
     * @var bool
     */
    protected $hasMore = false;

    /**
     * @inheritDoc
     */
    public function __construct(int $platform, $platformResponse)
    {
        parent::__construct($platform, $platformResponse);

        switch ($platform) {
            case PlatformInterface::PLATFORM_STRIPE:
                $this->hasMore = $platformResponse->has_more;
                $this->elements = $platformResponse->data;
                break;

            default:
                throw new PlatformNotYetImplemented();
        }
    }

    /**
     * @return bool
     */
    public function hasMore(): bool
    {
        return $this->hasMore;
    }

    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->elements[$offset]) ? $this->elements[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->elements[] = $value;
        } else {
            $this->elements[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }

    public function count()
    {
        return count($this->elements);
    }

    public function current()
    {
        return current($this->elements);
    }

    public function next()
    {
        return next($this->elements);
    }

    public function key()
    {
        return key($this->elements);
    }

    public function valid()
    {
        return key($this->elements) !== null;
    }

    public function rewind()
    {
        return reset($this->elements);
    }
}