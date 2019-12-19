<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\CustomerBundle\Adapter\Stripe\AbstractStripeAdapter;
use Softspring\SubscriptionBundle\Adapter\SubscriptionResponse;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;
use Softspring\CustomerBundle\Model\PlatformObjectInterface;
use Softspring\CustomerBundle\PlatformInterface;
use Stripe\Exception\InvalidRequestException;
use Stripe\Invoice;
use Stripe\Subscription as StripeSubscription;

class StripeSubscriptionAdapter extends AbstractStripeAdapter implements SubscriptionAdapterInterface
{
    /**
     * @inheritDoc
     */
    public function subscribe($customer, $plan, array $options = []): SubscriptionResponse
    {
        $customerId = $customer instanceof PlatformObjectInterface ? $customer->getPlatformId() : $customer;
        $planId = $plan instanceof PlatformObjectInterface ? $plan->getPlatformId() : $plan;

        try {
            $this->initStripe();

            $subscriptionConfig = [
                'customer' => $customerId,
                'items' => [['plan' => $planId]],
            ];

            if (!empty($options['trial_period_days'])) {
                $subscriptionConfig['trial_period_days'] = $options['trial_period_days'];
            }

            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::create($subscriptionConfig);

            $this->logger->info(sprintf('Stripe created subscription %s%s', $subscriptionData->id, !empty($options['trial_period_days']) ? ' with trial' : ''));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function trial($customer, $plan, int $days, array $options = []): SubscriptionResponse
    {
        $customerId = $customer instanceof PlatformObjectInterface ? $customer->getPlatformId() : $customer;
        $planId = $plan instanceof PlatformObjectInterface ? $plan->getPlatformId() : $plan;

        $options['trial_period_days'] = $days;
        return $this->subscribe($customerId, $planId, $options);
    }

    /**
     * @inheritDoc
     */
    public function details($subscription): SubscriptionResponse
    {
        $subscriptionId = $subscription instanceof PlatformObjectInterface ? $subscription->getPlatformId() : $subscription;

        try {
            $this->initStripe();

            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscriptionId,
            ]);

            $this->logger->info(sprintf('Stripe retrieve details for %s', $subscriptionId));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function cancel($subscription): SubscriptionResponse
    {
        $subscriptionId = $subscription instanceof PlatformObjectInterface ? $subscription->getPlatformId() : $subscription;

        try {
            $this->initStripe();

            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscriptionId,
            ]);
            $subscriptionData->updateAttributes([
                'cancel_at_period_end' => true,
            ]);
            $subscriptionData->save();

            $this->logger->info(sprintf('Stripe cancel renewal for %s', $subscriptionId));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function uncancel($subscription): SubscriptionResponse
    {
        $subscriptionId = $subscription instanceof PlatformObjectInterface ? $subscription->getPlatformId() : $subscription;

        try {
            $this->initStripe();

            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscriptionId,
            ]);
            $subscriptionData->updateAttributes([
                'cancel_at_period_end' => false,
            ]);
            $subscriptionData->save();

            $this->logger->info(sprintf('Stripe un cancel renewal for %s', $subscriptionId));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function upgrade($subscription, $plan, array $options = []): SubscriptionResponse
    {
        $subscriptionId = $subscription instanceof PlatformObjectInterface ? $subscription->getPlatformId() : $subscription;
        $planId = $plan instanceof PlatformObjectInterface ? $plan->getPlatformId() : $plan;

        try {
            $this->initStripe();

            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscriptionId,
            ]);
            $subscriptionData->updateAttributes([
                'plan' => $planId,
                'prorate' => true,
            ]);
            $subscriptionData->save();

            try {
                /** @var Invoice $invoice */
                $invoice = Invoice::create(["customer" => $subscriptionData->customer]);
                $invoice->finalizeInvoice();
            } catch (InvalidRequestException $e) {
                if ($e->getMessage() == 'Nothing to invoice for customer') {

                } else {
                    throw $e;
                }
            }

            $this->logger->info(sprintf('Stripe upgraded plan for %s', $subscriptionId));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function finishTrial($subscriptionId, $planId): SubscriptionResponse
    {
        $subscriptionId = $subscriptionId instanceof PlatformObjectInterface ? $subscriptionId->getPlatformId() : $subscriptionId;
        $planId = $planId instanceof PlatformObjectInterface ? $planId->getPlatformId() : $planId;

        try {
            $this->initStripe();

            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscriptionId,
            ]);
            $subscriptionData->updateAttributes([
                'plan' => $planId,
                'trial_end' => 'now',
            ]);
            $subscriptionData->save();

            $this->logger->info(sprintf('Stripe finish trial for %s', $subscriptionId));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }
}