<?php

namespace Softspring\SubscriptionBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Softspring\CustomerBundle\Model\PlatformObjectInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionItemInterface;
use Softspring\SubscriptionBundle\Tests\Model\Examples\PlanBaseExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionItemBaseExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionMultiPlanExample;

class SubscriptionItemTest extends TestCase
{
    public function testInterfaces()
    {
        $this->assertInstanceOf(SubscriptionItemInterface::class, new SubscriptionItemBaseExample());
        $this->assertInstanceOf(PlatformObjectInterface::class, new SubscriptionItemBaseExample());
    }

    public function testBasePlanGetterAndSetters()
    {
        $item = new SubscriptionItemBaseExample();

        $this->assertNull($item->getSubscription());
        $this->assertNull($item->getPlan());
        $this->assertEquals(1, $item->getQuantity());

        $plan = new PlanBaseExample();
        $item->setPlan($plan);
        $this->assertEquals($plan, $item->getPlan());

        $item->setQuantity(1);
        $this->assertEquals(1, $item->getQuantity());

        $subscription = new SubscriptionMultiPlanExample();
        $item->setSubscription($subscription);
        $this->assertEquals($subscription, $item->getSubscription());
    }
}
