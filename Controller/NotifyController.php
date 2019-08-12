<?php

namespace Softspring\SubscriptionBundle\Controller;

use Softspring\ExtraBundle\Controller\AbstractController;
use Softspring\SubscriptionBundle\Adapter\NotifyAdapterInterface;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class NotifyController extends AbstractController
{
    /**
     * @var NotifyAdapterInterface
     */
    protected $notifyAdapter;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * NotifyController constructor.
     * @param NotifyAdapterInterface $notifyAdapter
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(NotifyAdapterInterface $notifyAdapter, EventDispatcherInterface $eventDispatcher)
    {
        $this->notifyAdapter = $notifyAdapter;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function notify(Request $request): Response
    {
        try {
            $this->eventDispatcher->dispatch($this->notifyAdapter->createEvent($request), SfsSubscriptionEvents::NOTIFY);

            return new Response('', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
    }
}