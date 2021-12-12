<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\CustomerBundle\Model\CustomerInterface;

interface SubscriptionCustomerInterface extends CustomerInterface
{
    /**
     * @return string|null
     */
    public function getEmail(): ?string;

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
}