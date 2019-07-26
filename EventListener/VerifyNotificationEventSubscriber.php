<?php

namespace Softspring\SubscriptionBundle\EventListener;

use Softspring\SubscriptionBundle\Event\GetResponseEvent;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyNotificationEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            SfsSubscriptionEvents::NOTIFY_INIT => [['onNotifyVerifyRequest', -255]]
        ];
    }

    public function onNotifyVerifyRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$this->validRequest($request)) {
            $response = new Response('Verification fail', Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }

    protected function validRequest(Request $request): bool
    {
        return true;
    }
}