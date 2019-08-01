<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\ExtraBundle\Event\GetResponseTrait;
use Softspring\Subscription\Model\ClientInterface;
use Softspring\Subscription\Model\PlanInterface;
use Symfony\Component\HttpFoundation\Request;

class PreSubscribeGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var PlanInterface
     */
    protected $plan;

    /**
     * @var Request|null
     */
    protected $request;

    /**
     * PreSubscribeGetRequestEvent constructor.
     * @param ClientInterface $client
     * @param PlanInterface $plan
     * @param Request|null $request
     */
    public function __construct(ClientInterface $client, PlanInterface $plan, ?Request $request)
    {
        parent::__construct($request);
        $this->client = $client;
        $this->plan = $plan;
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @return PlanInterface
     */
    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }
}