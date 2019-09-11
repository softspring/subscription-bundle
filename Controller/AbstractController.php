<?php

namespace Softspring\SubscriptionBundle\Controller;

use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\ExtraBundle\Controller\AbstractController as ExtraAbstractController;
use Softspring\SubscriptionBundle\Model\ClientInterface;

abstract class AbstractController extends ExtraAbstractController
{
    /**
     * Ensures AccountInterface entity implements subscription ClientInterface
     *
     * @param AccountInterface $_account
     * @return ClientInterface
     */
    protected function getClient(AccountInterface $_account): ClientInterface
    {
        if (! $_account instanceof ClientInterface) {
            throw new \InvalidArgumentException(sprintf('Account "%s" class must implements "%s" to be used with subscriptions', get_class($_account), ClientInterface::class));
        }

        return $_account;
    }
}