<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\GetResponseTrait;

class GetResponsePlantEvent extends PlanEvent
{
    use GetResponseTrait;
}
