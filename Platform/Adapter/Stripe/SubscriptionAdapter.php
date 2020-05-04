<?php

namespace Softspring\SubscriptionBundle\Platform\Adapter\Stripe;

use Softspring\CustomerBundle\Platform\Adapter\Stripe\AbstractStripeAdapter;
use Softspring\CustomerBundle\Model\PlatformObjectInterface;
use Softspring\CustomerBundle\Platform\PlatformInterface;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Softspring\SubscriptionBundle\Manager\SubscriptionItemManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionItemInterface;
use Softspring\SubscriptionBundle\Platform\Adapter\SubscriptionAdapterInterface;
use Softspring\SubscriptionBundle\Platform\Response\SubscriptionResponse;
use Stripe\Exception\InvalidRequestException;
use Stripe\Invoice;
use Stripe\Subscription as StripeSubscription; use Stripe\SubscriptionItem as StripeSubscriptionItem;

class SubscriptionAdapter extends AbstractStripeAdapter implements SubscriptionAdapterInterface
{
    const MAPPING_STATUSES = [
        'active' => SubscriptionInterface::STATUS_ACTIVE,
        'incomplete' => SubscriptionInterface::STATUS_ACTIVE,
        'trialing' => SubscriptionInterface::STATUS_TRIALING,
        'unpaid' => SubscriptionInterface::STATUS_UNPAID,
        'past_due' => SubscriptionInterface::STATUS_UNPAID,
        'incomplete_expired' => SubscriptionInterface::STATUS_EXPIRED,
        'canceled' => SubscriptionInterface::STATUS_EXPIRED,
    ];

    /**
     * @var SubscriptionItemManagerInterface
     */
    protected $itemManager;

    /**
     * @var PlanManagerInterface
     */
    protected $planManager;

    /**
     * @param SubscriptionItemManagerInterface $itemManager
     */
    public function setItemManager(SubscriptionItemManagerInterface $itemManager): void
    {
        $this->itemManager = $itemManager;
    }

    /**
     * @param PlanManagerInterface $planManager
     */
    public function setPlanManager(PlanManagerInterface $planManager): void
    {
        $this->planManager = $planManager;
    }

    protected static function prepareDataForPlatform(SubscriptionInterface $subscription, string $action = ''): array
    {
        $data = [
            'subscription' => [
                'items' => [],
            ],
        ];

        if ($action == 'create') {
            $data['subscription']['customer'] = $subscription->getCustomer()->getPlatformId();
        }

        foreach ($subscription->getItems() as $item) {
            if ($action == 'create' || ($action == 'update' && !$item->getPlatformId())) {
                $data['subscription']['items'][] = [
                    'plan' => $item->getPlan()->getPlatformId(),
                    'quantity' => $item->getQuantity(),
                ];
            } elseif ($action == 'update' && $item->getPlatformId()) {
                $data['subscription']['items'][] = [
                    'id' => $item->getPlatformId(),
                    'plan' => $item->getPlan()->getPlatformId(),
                    'quantity' => $item->getQuantity(),
                ];
            }
        }

        return $data;
    }

    protected function getSubscriptionItem(SubscriptionInterface $subscription, StripeSubscriptionItem $itemStripe): SubscriptionItemInterface
    {
        foreach ($subscription->getItems() as $item) {
            if ($item->getPlatformId() == $itemStripe->id) {
                return $item;
            }
        }

        foreach ($subscription->getItems() as $item) {
            if ($item->getPlatformId()) {
                continue;
            }

            if ($item->getPlan()->getPlatformId() != $itemStripe->plan->id) {
                continue;
            }

            if ($item->getQuantity() != $itemStripe->quantity) {
                continue;
            }

            return $item;
        }

        $subscription->addItem($newItem = $this->itemManager->createEntity());
        $newItem->setQuantity($itemStripe->quantity);
        $newItem->setPlan($this->planManager->getRepository()->findOneByPlatformId($itemStripe->plan));

        return $newItem;
    }

    public function syncSubscription(SubscriptionInterface $subscription, StripeSubscription $subscriptionStripe)
    {
        // save platform data
        $subscription->setPlatform(PlatformInterface::PLATFORM_STRIPE);
        $subscription->setPlatformId($subscriptionStripe->id);
        $subscription->setTestMode(!$subscriptionStripe->livemode);
        $subscription->setPlatformLastSync(\DateTime::createFromFormat('U', $subscriptionStripe->created));
        $subscription->setPlatformConflict(false);
        $subscription->setPlatformData($subscriptionStripe->toArray());

        foreach ($subscriptionStripe->items as $itemStripe) {
            $subscriptionItem = $this->getSubscriptionItem($subscription, $itemStripe);
            $subscriptionItem->setPlatform(PlatformInterface::PLATFORM_STRIPE);
            $subscriptionItem->setPlatformId($itemStripe->id);
            $subscriptionItem->setTestMode(!$itemStripe->livemode);
            $subscriptionItem->setPlatformLastSync(\DateTime::createFromFormat('U', $itemStripe->created));
            $subscriptionItem->setPlatformConflict(false);
            $subscriptionItem->setPlatformData($itemStripe->toArray());
        }

        $subscription->setStartDate(\DateTime::createFromFormat('U', $subscriptionStripe->current_period_start));
        $subscription->setEndDate(\DateTime::createFromFormat('U', $subscriptionStripe->current_period_end));
        $subscription->setStatus(self::MAPPING_STATUSES[$subscriptionStripe->status]);

        if (!empty($subscriptionStripe->cancel_at)) {
            if (in_array($subscription->getStatus(), [SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::STATUS_TRIALING])) {
                $subscription->setStatus(SubscriptionInterface::STATUS_CANCELED);
            }

            $subscription->setCancelScheduled(\DateTime::createFromFormat('U', $subscriptionStripe->cancel_at));
        } else {
            $subscription->setCancelScheduled(null);
        }
    }

    public function create(SubscriptionInterface $subscription)
    {
        try {
            $this->initStripe();

            // prepare data for stripe
            $data = self::prepareDataForPlatform($subscription, 'create');

            /** @var StripeSubscription $subscriptionStripe */
            $subscriptionStripe = $this->stripeClientCreate($data['subscription']);

            $this->logger && $this->logger->info(sprintf('Stripe created subscription %s', $subscriptionStripe->id));

            $this->syncSubscription($subscription, $subscriptionStripe);

            return $subscriptionStripe;
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(SubscriptionInterface $subscription)
    {
        try {
            $this->initStripe();

            $response = $this->stripeClientRetrieve([
                'id' => $subscription->getPlatformId(),
            ]);

            $this->syncSubscription($subscription, $response);

            return $response;
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function update(SubscriptionInterface $subscription)
    {
        try {
            $this->initStripe();

            // prepare data for stripe
            $data = self::prepareDataForPlatform($subscription, 'update');

            /** @var  $subscriptionStripe */
            $subscriptionStripe = $this->get($subscription);
            $subscriptionStripe->updateAttributes($data['subscription']);
            $subscriptionStripe->save();

            $this->logger && $this->logger->info(sprintf('Stripe updated customer %s', $subscriptionStripe->id));

            $this->syncSubscription($subscription, $subscriptionStripe);

            return $subscriptionStripe;
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     * @deprecated
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

            $this->logger && $this->logger->info(sprintf('Stripe created subscription %s%s', $subscriptionData->id, !empty($options['trial_period_days']) ? ' with trial' : ''));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     * @deprecated
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
     * @deprecated Use get method
     */
    public function details($subscription): SubscriptionResponse
    {
        return $this->get($subscription);
    }

    /**
     * @inheritDoc
     */
    public function cancelRenovation($subscription): SubscriptionResponse
    {
        $subscriptionId = $subscription instanceof PlatformObjectInterface ? $subscription->getPlatformId() : $subscription;

        try {
            $this->initStripe();

            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = $this->stripeClientRetrieve([
                'id' => $subscriptionId,
            ]);
            $subscriptionData->updateAttributes([
                'cancel_at_period_end' => true,
            ]);
            $subscriptionData->save();

            $this->logger && $this->logger->info(sprintf('Stripe cancel renewal for %s', $subscriptionId));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function uncancelRenovation($subscription): SubscriptionResponse
    {
        $subscriptionId = $subscription instanceof PlatformObjectInterface ? $subscription->getPlatformId() : $subscription;

        try {
            $this->initStripe();

            /** @var StripeSubscription $subscriptionData */
            $subscriptionData = $this->stripeClientRetrieve([
                'id' => $subscriptionId,
            ]);
            $subscriptionData->updateAttributes([
                'cancel_at_period_end' => false,
            ]);
            $subscriptionData->save();

            $this->logger && $this->logger->info(sprintf('Stripe un cancel renewal for %s', $subscriptionId));

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
            $subscriptionData = $this->stripeClientRetrieve([
                'id' => $subscriptionId,
            ]);
            $subscriptionData->cancel();

            $this->logger && $this->logger->info(sprintf('Stripe deleted %s subscription', $subscriptionId));

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
            $subscriptionData = $this->stripeClientRetrieve([
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

            $this->logger && $this->logger->info(sprintf('Stripe upgraded plan for %s', $subscriptionId));

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
            $subscriptionData = $this->stripeClientRetrieve([
                'id' => $subscriptionId,
            ]);
            $subscriptionData->updateAttributes([
                'plan' => $planId,
                'trial_end' => 'now',
            ]);
            $subscriptionData->save();

            $this->logger && $this->logger->info(sprintf('Stripe finish trial for %s', $subscriptionId));

            return new SubscriptionResponse(PlatformInterface::PLATFORM_STRIPE, $subscriptionData);
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }

    protected function stripeClientCreate($params = null, $options = null): StripeSubscription
    {
        return StripeSubscription::create($params, $options);
    }

    protected function stripeClientRetrieve($id, $opts = null): StripeSubscription
    {
        return StripeSubscription::retrieve($id, $opts);
    }
}