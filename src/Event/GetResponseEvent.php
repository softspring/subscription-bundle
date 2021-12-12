<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\GetResponseEventInterface;
use Softspring\CoreBundle\Event\GetResponseTrait;
use Softspring\CoreBundle\Event\RequestEvent;

class GetResponseEvent extends RequestEvent implements GetResponseEventInterface
{
    use GetResponseTrait;
}