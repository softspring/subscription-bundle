<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\SubscriptionBundle\Model\CustomerInterface;
use Softspring\SubscriptionBundle\Adapter\CustomerAdapterInterface;
use Softspring\SubscriptionBundle\PlatformInterface;
use Stripe\Card;
use Stripe\Customer;

class StripeCustomerAdapter extends AbstractStripeAdapter implements CustomerAdapterInterface
{
    /**
     * @inheritDoc
     */
    public function create(CustomerInterface $customer): string
    {
        $this->initStripe();

        $data = [
            'email' => $customer->getEmail(),
        ];

        // 'business_vat_id' => $account->getVatNumber(),
        // 'shipping' => [
        //     'name' => $account->getCompanyName(),
        //     'address' => [
        //         'line1' => $account->getAddress(),
        //         'postal_code' => $account->getZipCode(),
        //         'country' => $account->getCountry(),
        //     ],

        return Customer::create($data)->id;
    }

    /**
     * @inheritDoc
     */
    public function getData(CustomerInterface $client)
    {
        $this->initStripe();

        /** @var Customer $customer */
        return Customer::retrieve([
            'id' => $client->getPlatformId(),
        ]);
    }

    /**
     * @param CustomerInterface $customer
     * @param string            $token
     *
     * @return array
     */
    public function addCard(CustomerInterface $customer, string $token)
    {
        $customer = $this->getData($customer);

        /** @var Card $source */
        $source = $customer->sources->create(['source' => $token]);

        $customer->default_source = $source;
        $customer->save();

        return $source;
    }
}