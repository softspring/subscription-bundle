<?php

namespace Softspring\SubscriptionBundle\Model;

use Softspring\SubscriptionBundle\Entity\CustomerSubscriptionsTrait as EntityClientSubscriptionsTrait;

/**
 * Trait ClientSubscriptionsTrait
 *
 * @deprecated Use Softspring\SubscriptionBundle\Entity\ClientSubscriptionsTrait instead
 */
trait CustomerSubscriptionsTrait
{
    use EntityClientSubscriptionsTrait;
}