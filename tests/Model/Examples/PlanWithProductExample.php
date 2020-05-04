<?php

namespace Softspring\SubscriptionBundle\Tests\Model\Examples;

use Softspring\CustomerBundle\Model\PlatformObjectTrait;
use Softspring\SubscriptionBundle\Model\Plan;
use Softspring\SubscriptionBundle\Model\PlanHasProductInterface;
use Softspring\SubscriptionBundle\Model\PlanProductRelationTrait;

class PlanWithProductExample extends Plan implements PlanHasProductInterface
{
    use PlatformObjectTrait;
    use PlanProductRelationTrait;
}