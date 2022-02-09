<?php

namespace Softspring\SubscriptionBundle\Event;

use Softspring\CoreBundle\Event\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Symfony\Component\HttpFoundation\Request;

class PreSubscribeGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var SubscriptionCustomerInterface
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
     */
    public function __construct(SubscriptionCustomerInterface $client, PlanInterface $plan, ?Request $request)
    {
        parent::__construct($request);
        $this->client = $client;
        $this->plan = $plan;
    }

    public function getClient(): SubscriptionCustomerInterface
    {
        return $this->client;
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }
}
