<?php

namespace Softspring\SubscriptionBundle\Controller;

use Softspring\ExtraBundle\Controller\AbstractController;
use Softspring\ExtraBundle\Controller\Traits\DispatchGetResponseTrait;
use Softspring\SubscriptionBundle\Event\GetResponseEvent;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotifyController extends AbstractController
{
    use DispatchGetResponseTrait;

    public function subscriptionTrialEnd(Request $request): Response
    {
        if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::NOTIFY_INIT, new GetResponseEvent($request))) {
            return $response;
        }




        return new Response('', Response::HTTP_OK);
    }
}