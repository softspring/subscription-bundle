<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\SubscriptionBundle\Model\CustomerInterface;

/**
 * Interface CustomerAdapterInterface
 */
interface CustomerAdapterInterface
{
    /**
     * Creates customer on defined platform
     *
     * @param CustomerInterface $customer
     *
     * @return string
     */
    public function create(CustomerInterface $customer): string;

    /**
     * @param CustomerInterface $customer
     *
     * @return object|array
     */
    public function getData(CustomerInterface $customer);

    /**
     * @param CustomerInterface $customer
     * @param string            $token
     *
     * @return object|array
     */
    public function addCard(CustomerInterface $customer, string $token);
}