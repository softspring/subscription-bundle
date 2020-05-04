<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

/**
 * Interface CustomerHasTriedInterface
 * @deprecated
 */
interface CustomerHasTriedInterface
{
    /**
     * @return bool
     */
    public function hasTried(): bool;

    /**
     * @param bool $tried
     */
    public function setTried(bool $tried): void;
}