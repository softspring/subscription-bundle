<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\CustomerBundle\Adapter\PlatformAdapterInterface;
use Softspring\SubscriptionBundle\Event\NotifyEvent;
use Symfony\Component\HttpFoundation\Request;

interface NotifyAdapterInterface extends PlatformAdapterInterface
{
    public function createEvent(Request $request): NotifyEvent;
}