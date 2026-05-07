<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\CustomerBundle\Model\CustomerInterface;

interface SubscriptionCustomerInterface extends CustomerInterface
{
    public function getEmail(): ?string;

    /**
     * @return Collection|SubscriptionInterface[]
     */
    public function getSubscriptions(): Collection;

    public function addSubscription(SubscriptionInterface $subscription): void;

    /**
     * @return Collection|SubscriptionInterface[]
     */
    public function getActiveSubscriptions(): Collection;
}
