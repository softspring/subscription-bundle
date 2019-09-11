<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\SubscriptionBundle\Model\ClientInterface;
use Softspring\SubscriptionBundle\Adapter\ClientAdapterInterface;
use Stripe\Card;
use Stripe\Customer;

class StripeClientAdapter extends AbstractStripeAdapter implements ClientAdapterInterface
{
    public function getClientData(ClientInterface $client)
    {
        $this->initStripe();

        /** @var Customer $customer */
        return Customer::retrieve([
            'id' => $client->getPlatformId(),
        ]);
    }

    public function customerAddToken(ClientInterface $client, string $token)
    {
        $customer = $this->getClientData($client);

        /** @var Card $source */
        $source = $customer->sources->create(['source' => $token]);

        $customer->default_source = $source;
        $customer->save();

        return $source;
    }
}