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
     * StripeClient constructor.
     *
     * @param string $apiSecretKey
     */
    public function __construct(string $apiSecretKey)
    {
        $this->apiSecretKey = $apiSecretKey;
    }

    protected function initStripe()
    {
        Stripe::setApiKey($this->apiSecretKey);
    }
}