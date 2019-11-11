<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\ShopBundle\Model\Customer;
use Softspring\SubscriptionBundle\Model\CustomerInterface;

interface SourceManagerInterface
{
    /**
     * @param CustomerInterface $customer
     * @param string            $sourceId
     *
     * @return array
     */
    public function getSource(CustomerInterface $customer, string $sourceId): array;
}