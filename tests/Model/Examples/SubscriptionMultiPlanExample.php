<?php

namespace Softspring\SubscriptionBundle\Tests\Model\Examples;

use Doctrine\Common\Collections\ArrayCollection;
use Softspring\SubscriptionBundle\Model\Subscription;
use Softspring\SubscriptionBundle\Model\SubscriptionMultiPlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionMultiPlanTrait;

class SubscriptionMultiPlanExample extends Subscription implements SubscriptionMultiPlanInterface
{
    use SubscriptionMultiPlanTrait;

    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
    }
}