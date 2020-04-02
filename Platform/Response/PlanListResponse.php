<?php

namespace Softspring\SubscriptionBundle\Platform\Response;

use Softspring\CustomerBundle\Platform\Response\AbstractListResponse;

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