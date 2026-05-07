<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Event;

use Softspring\Component\Events\GetResponseTrait;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Symfony\Component\HttpFoundation\Request;

class PreSubscribeGetResponseEvent extends GetResponseEvent
{
    use GetResponseTrait;

    /**
     * @var Request|null
     */
    protected $request;

    public function __construct(
        protected SubscriptionCustomerInterface $client,
        protected PlanInterface $plan,
        ?Request $request,
    ) {
        parent::__construct($request);
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
