<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\Subscription\Model\PlanInterface;
use Softspring\SubscriptionBundle\Adapter\PlanAdapterInterface;

class StripePlanAdapter extends AbstractStripeAdapter implements PlanAdapterInterface
{
    public function create(PlanInterface $plan): void
    {
        // TODO: Implement create() method.
    }

    public function update(PlanInterface $plan): void
    {
        // TODO: Implement update() method.
    }

    public function delete(PlanInterface $plan): void
    {
        // TODO: Implement delete() method.
    }
}