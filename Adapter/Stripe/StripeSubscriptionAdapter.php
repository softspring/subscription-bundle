<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\SubscriptionBundle\Exception\MaxSubscriptionsReachException;
use Softspring\SubscriptionBundle\Model\CustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\PlatformInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;
use Softspring\SubscriptionBundle\Exception\MissingPlatformIdException;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Invoice;
use Stripe\Subscription;
use Stripe\Subscription as StripeSubscription;

class StripeSubscriptionAdapter extends AbstractStripeAdapter implements SubscriptionAdapterInterface
{
    public function subscribe(SubscriptionInterface $subscription, CustomerInterface $client, PlanInterface $plan): void
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

            $subscription->setCustomer($client);
            $subscription->setPlan($plan);

            $subscription->setPlatform(PlatformInterface::PLATFORM_STRIPE);
            $subscription->setPlatformId($subscriptionData->id);
            $subscription->setStartDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_start));
            $subscription->setEndDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_end));
            $this->setStatus($subscriptionData, $subscription);

        } catch (InvalidRequestException $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }

    public function trial(SubscriptionInterface $subscription, CustomerInterface $client, PlanInterface $plan): void
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

            $subscription->setCustomer($client);
            $subscription->setPlan($plan);

            $subscription->setPlatform(PlatformInterface::PLATFORM_STRIPE);
            $subscription->setPlatformId($subscriptionData->id);
            $subscription->setStartDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_start));
            $subscription->setEndDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_end));
            $this->setStatus($subscriptionData, $subscription);

        } catch (InvalidRequestException $e) {
            switch ($e->getStripeCode()) {
                case 'customer_max_subscriptions':
                    throw new MaxSubscriptionsReachException($e->getMessage(), 0, $e);
                    break;

                default:
                    throw new SubscriptionException('Invalid stripe request', 0, $e);
            }
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

            return $subscriptionData->toArray(true);
        } catch (InvalidRequestException $e) {
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

            return $subscriptionData->toArray(true);
        } catch (InvalidRequestException $e) {
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

            return $subscriptionData->toArray(true);
        } catch (InvalidRequestException $e) {
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
            } catch (InvalidRequestException $e) {
                if ($e->getMessage() == 'Nothing to invoice for customer') {

                } else {
                    throw $e;
                }
            }

            return $subscriptionData->toArray(true);
        } catch (InvalidRequestException $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }

    public function finishTrial(SubscriptionInterface $subscription, PlanInterface $plan): void
    {
        if (!$subscription->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        if (!$plan->getPlatformId()) {
            throw new MissingPlatformIdException();
        }

        if ($subscription->getStatus() != SubscriptionInterface::STATUS_TRIALING) {
            throw new SubscriptionException('Subscription is not trialing now');
        }

        $this->initStripe();

        try {
            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = StripeSubscription::retrieve([
                'id' => $subscription->getPlatformId(),
            ]);

            $subscriptionData->updateAttributes([
                'plan' => $plan->getPlatformId(),
                'trial_end' => 'now',
            ]);

            $subscriptionData->save();

            $subscription->setPlan($plan);
            $subscription->setStartDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_start));
            $subscription->setEndDate(\DateTime::createFromFormat('U', $subscriptionData->current_period_end));
            $this->setStatus($subscriptionData, $subscription);

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

            case 'trialing':
                $subscription->setStatus(SubscriptionInterface::STATUS_TRIALING);
                break;

            default:
                throw new SubscriptionException('Not yet implemented');
        }
    }
}