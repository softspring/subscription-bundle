<?php

namespace Softspring\SubscriptionBundle\Adapter;

class PlanListResponse extends AbstractListResponse
{
    public function __construct(int $platform, $platformResponse)
    {
        parent::__construct($platform, $platformResponse);

        foreach ($this->elements as $i => $element) {
            $this->elements[$i] = new PlanResponse($platform, $element);
        }
    }
}