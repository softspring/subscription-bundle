<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\ExtraBundle\Event\GetResponseEventInterface;
use Softspring\ExtraBundle\Event\GetResponseTrait;

class GetResponseEvent extends Event implements GetResponseEventInterface
{
    use GetResponseTrait;
}