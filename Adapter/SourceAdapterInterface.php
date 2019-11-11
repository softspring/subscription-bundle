<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\SubscriptionBundle\Model\CustomerInterface;

interface SourceAdapterInterface
{
    /**
     * @param CustomerInterface $customer
     * @param string            $sourceId
     *
     * @return array
     */
    public function getSource(CustomerInterface $customer, string $sourceId): array;
}