<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\SubscriptionBundle\Event\NotifyEvent;
use Symfony\Component\HttpFoundation\Request;

interface NotifyAdapterInterface
{
    public function createEvent(Request $request): NotifyEvent;
}