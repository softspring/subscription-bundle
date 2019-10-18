<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\GetResponseEventInterface;
use Softspring\CoreBundle\Event\GetResponseTrait;

class GetResponseEvent extends Event implements GetResponseEventInterface
{
    use GetResponseTrait;
}