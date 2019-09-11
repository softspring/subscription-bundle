<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\SubscriptionBundle\Model\ClientInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\PlatformInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;
use Softspring\SubscriptionBundle\Exception\MissingPlatformIdException;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Stripe\Error\InvalidRequest;
use Stripe\Invoice;
use Stripe\Subscription;
use Stripe\Subscription as StripeSubscription;

class StripeSubscriptionAdapter extends AbstractStripeAdapter implements SubscriptionAdapterInterface
{
    public function subscribe(SubscriptionInterface $subscription, ClientInterface $client, PlanInterface $plan): void
    {
        if (!$client->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        if (!$plan->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        $this->initStripe();

        try {
            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::create([
                'customer' => $client->getPlatformId(),
                'items' => [['plan' => $plan->getPlatformId()]],
            ]);

            $subscription->setClient($client);
            $subscription->setPlan($plan);

            $subscription->setPlatform(PlatformInterface::PLATFORM_STRIPE);
            $subscription->setPlatformId($subscriptionData->id);
            $subscription->setStartDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_start));
            $subscription->setEndDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_end));
            $this->setStatus($subscriptionData, $subscription);

        } catch (InvalidRequest $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }

    public function trial(SubscriptionInterface $subscription, ClientInterface $client, PlanInterface $plan): void
    {
        if (!$client->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        if (!$plan->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        $this->initStripe();

        try {
            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::create([
                'customer' => $client->getPlatformId(),
                'items' => [['plan' => $plan->getPlatformId()]],
                'trial_period_days' => 7,
            ]);

            $subscription->setClient($client);
            $subscription->setPlan($plan);

            $subscription->setPlatform(PlatformInterface::PLATFORM_STRIPE);
            $subscription->setPlatformId($subscriptionData->id);
            $subscription->setStartDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_start));
            $subscription->setEndDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_end));

            switch ($subscriptionData->status) {
                case 'trialing':
                    $subscription->setStatus(SubscriptionInterface::STATUS_TRIALING);
                    break;
            }

        } catch (InvalidRequest $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }

    public function details(SubscriptionInterface $subscription): array
    {
        if (!$subscription->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        $this->initStripe();

        try {
            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscription->getPlatformId(),
            ]);

            return $subscriptionData->__toArray(true);
        } catch (InvalidRequest $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }

    public function cancel(SubscriptionInterface $subscription): array
    {
        if (!$subscription->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        $this->initStripe();

        try {
            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscription->getPlatformId(),
            ]);

            $subscriptionData->updateAttributes([
                'cancel_at_period_end' => true,
            ]);

            $subscriptionData->save();

            $subscription->setCancelScheduled(\DateTime::createFromFormat('U', $subscriptionData->cancel_at));

            return $subscriptionData->__toArray(true);
        } catch (InvalidRequest $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }

    public function uncancel(SubscriptionInterface $subscription): array
    {
        if (!$subscription->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        $this->initStripe();

        try {
            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscription->getPlatformId(),
            ]);

            $subscriptionData->updateAttributes([
                'cancel_at_period_end' => false,
            ]);

            $subscriptionData->save();

            $subscription->setCancelScheduled(null);

            return $subscriptionData->__toArray(true);
        } catch (InvalidRequest $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }

    public function upgrade(SubscriptionInterface $subscription, PlanInterface $plan): array
    {
        if (!$subscription->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        $this->initStripe();

        try {
            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscription->getPlatformId(),
            ]);

            $subscriptionData->updateAttributes([
                'plan' => $plan->getPlatformId(),
                'prorate' => true,
            ]);

            $subscriptionData->save();

            $subscription->setPlan($plan);

            try {
                /** @var Invoice $invoice */
                $invoice = Invoice::create(["customer" => $subscriptionData->customer]);
                $invoice->finalizeInvoice();
            } catch (InvalidRequest $e) {
                if ($e->getMessage() == 'Nothing to invoice for customer') {

                } else {
                    throw $e;
                }
            }

            return $subscriptionData->__toArray(true);
        } catch (InvalidRequest $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }

    /**
     * @param StripeSubscription $stripe
     * @param SubscriptionInterface $subscription
     * @throws SubscriptionException
     */
    protected function setStatus(Subscription $stripe, SubscriptionInterface $subscription)
    {
        switch ($stripe->status) {
            case 'active':
                $subscription->setStatus(SubscriptionInterface::STATUS_ACTIVE);
                break;

            default:
                throw new SubscriptionException('Not yet implemented');
        }
    }
}