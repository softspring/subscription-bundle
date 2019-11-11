<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\SubscriptionBundle\Adapter\SourceAdapterInterface;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\CustomerInterface;
use Stripe\Customer;
use Stripe\Exception\InvalidRequestException;

class StripeSourceAdapter extends AbstractStripeAdapter implements SourceAdapterInterface
{
    /**
     * @inheritDoc
     */
    public function getSource(CustomerInterface $customer, string $sourceId): array
    {
        $this->initStripe();

        try {
            $data = Customer::retrieveSource($customer->getPlatformId(), $sourceId);

            return $data->toArray();
        } catch (InvalidRequestException $e) {
            throw new SubscriptionException('Invalid stripe request', 0, $e);
        } catch (\Exception $e) {
            throw new SubscriptionException('Unknown stripe exception', 0, $e);
        }
    }
}