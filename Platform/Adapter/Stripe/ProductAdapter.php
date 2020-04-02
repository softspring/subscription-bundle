<?php

namespace Softspring\SubscriptionBundle\Platform\Adapter\Stripe;

use Softspring\CustomerBundle\Platform\Adapter\Stripe\AbstractStripeAdapter;
use Softspring\SubscriptionBundle\Model\ProductInterface;
use Softspring\CustomerBundle\Platform\PlatformInterface;
use Softspring\SubscriptionBundle\Platform\Adapter\ProductAdapterInterface;
use Softspring\SubscriptionBundle\Platform\Response\ProductListResponse;
use Stripe\Product as StripeProduct;

class ProductAdapter extends AbstractStripeAdapter implements ProductAdapterInterface
{
    public function create(ProductInterface $product): void
    {
        // TODO: Implement create() method.
    }

    public function update(ProductInterface $product): void
    {
        // TODO: Implement update() method.
    }

    public function delete(ProductInterface $product): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function list(): ProductListResponse
    {
        try {
            $this->initStripe();
            return new ProductListResponse(PlatformInterface::PLATFORM_STRIPE, StripeProduct::all());
        } catch (\Exception $e) {
            $this->attachStripeExceptions($e);
        }
    }
}