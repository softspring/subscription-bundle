<?php

namespace Softspring\SubscriptionBundle\Model;

trait SubscriptionStatusStringTrait
{
    public function getStatusString(): ?string
    {
        switch ($this->getStatus()) {
            case SubscriptionInterface::STATUS_TRIALING:
                return 'trialing';

            case SubscriptionInterface::STATUS_ACTIVE:
                return 'active';

            case SubscriptionInterface::STATUS_UNPAID:
                return 'unpaid';

            case SubscriptionInterface::STATUS_CANCELED:
                return 'canceled';

            case SubscriptionInterface::STATUS_SUSPENDED:
                return 'suspended';

            case SubscriptionInterface::STATUS_EXPIRED:
                return 'expired';
        }

        return null;
    }
}