<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\Subscription\Model\ClientInterface;
use Softspring\Subscription\Model\PlanInterface;
use Softspring\Subscription\PlatformInterface;
use Softspring\Subscription\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Adapter\SubscriptionAdapterInterface;
use Softspring\SubscriptionBundle\Exception\MissingPlatformIdException;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Stripe\Error\InvalidRequest;
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

            switch ($subscriptionData->status) {
                case 'active':
                    $subscription->setStatus(SubscriptionInterface::STATUS_ACTIVE);
                    break;
            }

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
}