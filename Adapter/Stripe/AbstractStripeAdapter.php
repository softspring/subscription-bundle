<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

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

    protected function initStripe()
    {
        Stripe::setApiKey($this->apiSecretKey);
    }
}