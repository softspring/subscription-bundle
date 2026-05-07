<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\GetResponseEventInterface;
use Softspring\Component\Events\GetResponseTrait;
use Softspring\Component\Events\RequestEvent;

class GetResponseEvent extends RequestEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}
