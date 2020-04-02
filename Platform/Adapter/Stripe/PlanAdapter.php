<?php

namespace Softspring\SubscriptionBundle\Platform\Adapter\Stripe;

use Softspring\CustomerBundle\Platform\Adapter\Stripe\AbstractStripeAdapter;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\CustomerBundle\Platform\PlatformInterface;
use Softspring\SubscriptionBundle\Platform\Adapter\PlanAdapterInterface;
use Softspring\SubscriptionBundle\Platform\Response\PlanListResponse;
use Stripe\Plan as StripePlan;

class PlanAdapter extends AbstractStripeAdapter implements PlanAdapterInterface
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