<?php

namespace Softspring\SubscriptionBundle\Tests\Model\Examples;

use Softspring\SubscriptionBundle\Model\Plan;
use Softspring\SubscriptionBundle\Model\PlanHasProductInterface;
use Softspring\SubscriptionBundle\Model\PlanProductRelationTrait;

class PlanWithProductExample extends Plan implements PlanHasProductInterface
{
    use PlanProductRelationTrait;
}