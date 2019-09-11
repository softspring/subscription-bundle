<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

interface ClientInterface extends PlatformObjectInterface
{
    /**
     * @return Collection|SubscriptionInterface[]
     */
    public function getSubscriptions(): Collection;

    /**
     * @param SubscriptionInterface $subscription
     */
    public function addSubscription(SubscriptionInterface $subscription): void;

    /**
     * @return Collection|SubscriptionInterface[]
     */
    public function getActiveSubscriptions(): Collection;

    /**
     * @return bool
     */
    public function hasTried(): bool;

    /**
     * @param bool $tried
     */
    public function setTried(bool $tried): void;
}