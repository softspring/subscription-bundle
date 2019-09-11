<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\SubscriptionBundle\Model\ClientInterface;

interface ClientAdapterInterface
{
    /**
     * @param ClientInterface $client
     * @return object|array
     */
    public function getClientData(ClientInterface $client);
}