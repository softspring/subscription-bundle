<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\CustomerBundle\Model\CustomerInterface;

interface SubscriptionCustomerInterface extends CustomerInterface
{
    public function getEmail(): ?string;

    public function getSubscriptions(): Collection;

    public function addSubscription(SubscriptionInterface $subscription): void;

    public function getActiveSubscriptions(): Collection;
}
