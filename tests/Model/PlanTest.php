<?php

namespace Softspring\SubscriptionBundle\Tests\Model;

use Softspring\CustomerBundle\Model\PlatformObjectInterface;
use Softspring\SubscriptionBundle\Model\PlanHasProductInterface;
use Softspring\SubscriptionBundle\Tests\Model\Examples\PlanBaseExample;
use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Tests\Model\Examples\PlanWithProductExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\ProductBaseExample;

class PlanTest extends TestCase
{
    public function testInterfaces()
    {
        $this->assertInstanceOf(PlanInterface::class, new PlanBaseExample());
        $this->assertInstanceOf(PlatformObjectInterface::class, new PlanBaseExample());
        $this->assertInstanceOf(PlanHasProductInterface::class, new PlanWithProductExample());
    }

    public function testBasePlanGetterAndSetters()
    {
        $plan = new PlanBaseExample();

        $this->assertNull($plan->getPlanKey());
        $this->assertNull($plan->getName());
        $this->assertNull($plan->getCurrency());
        $this->assertNull($plan->getAmount());
        $this->assertNull($plan->getInterval());
        $this->assertFalse($plan->isActive());
        $this->assertFalse($plan->isOnline());

        $plan->setName('test');
        $this->assertEquals('test', $plan->getName());
        $plan->setCurrency('test');
        $this->assertEquals('test', $plan->getCurrency());
        $plan->setAmount(12.34);
        $this->assertEquals(12.34, $plan->getAmount());
        $plan->setInterval(30);
        $this->assertEquals(30, $plan->getInterval());

        $plan->setActive(true);
        $this->assertTrue($plan->isActive());

        $plan->setOnline(true);
        $this->assertTrue($plan->isOnline());
    }

    public function testPlanWithProductGetterAndSetters()
    {
        $plan = new PlanWithProductExample();

        $this->assertNull($plan->getProduct());

        $product = new ProductBaseExample();
        $plan->setProduct($product);
        $this->assertEquals($product, $plan->getProduct());
    }
}
