<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\SubscriptionBundle\Adapter\PlanListResponse;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Adapter\PlanAdapterInterface;
use Softspring\SubscriptionBundle\PlatformInterface;
use Stripe\Plan as StripePlan;

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

    /**
     * @inheritDoc
     */
    public function list(): PlanListResponse
    {
        try {
            $this->initStripe();
            return new PlanListResponse(PlatformInterface::PLATFORM_STRIPE, StripePlan::all());
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }
}