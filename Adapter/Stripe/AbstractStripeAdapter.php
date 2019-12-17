<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\SubscriptionBundle\Exception\MaxSubscriptionsReachException;
use Softspring\SubscriptionBundle\Exception\NotFoundInPlatform;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Stripe;

abstract class AbstractStripeAdapter
{
    /**
     * @var string
     */
    protected $apiSecretKey;

    /**
     * @var string|null
     */
    protected $webhookSigningSecret;

    /**
     * AbstractStripeAdapter constructor.
     * @param string $apiSecretKey
     * @param string|null $webhookSigningSecret
     */
    public function __construct(string $apiSecretKey, ?string $webhookSigningSecret)
    {
        $this->apiSecretKey = $apiSecretKey;
        $this->webhookSigningSecret = $webhookSigningSecret;
    }

    /**
     * Initialize stripe client object
     */
    protected function initStripe()
    {
        Stripe::setApiKey($this->apiSecretKey);
    }

    /**
     * @param \Throwable $e
     *
     * @throws MaxSubscriptionsReachException
     * @throws NotFoundInPlatform
     * @throws SubscriptionException
     */
    protected function attachStripeExceptions(\Throwable $e): void
    {
        if ($e instanceof InvalidRequestException) {
            switch ($e->getStripeCode()) {
                case 'customer_max_subscriptions':
                    throw new MaxSubscriptionsReachException($e->getMessage(), 0, $e);

                case 'resource_missing':
                    throw new NotFoundInPlatform($e->getMessage(), 0, $e);

                default:
                    throw new SubscriptionException('Invalid stripe request', 0, $e);
            }
        }

        throw new SubscriptionException('Unknown stripe exception', 0, $e);
    }
}