<?php

namespace Softspring\SubscriptionBundle\Tests\Model;

use Softspring\CustomerBundle\Model\PlatformObjectInterface;
use Softspring\SubscriptionBundle\Model\ProductInterface;
use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\Tests\Model\Examples\PlanBaseExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\PlanWithProductExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\ProductBaseExample;

class ProductTest extends TestCase
{
    public function testInterfaces()
    {
        $this->assertInstanceOf(ProductInterface::class, new ProductBaseExample());
        $this->assertInstanceOf(PlatformObjectInterface::class, new ProductBaseExample());
        $this->assertInstanceOf(PlatformObjectInterface::class, new ProductBaseExample());
    }

    public function testBasePlanGetterAndSetters()
    {
        $product = new ProductBaseExample();

        $this->assertNull($product->getName());

        $product->setName('test');
        $this->assertEquals('test', $product->getName());

        $product->setType(ProductInterface::TYPE_GOOD_DIGITAL);
        $this->assertEquals(ProductInterface::TYPE_GOOD_DIGITAL, $product->getType());
    }

    public function testPlans()
    {
        $product = new ProductBaseExample();
        $plan1 = new PlanBaseExample();
        $plan2 = new PlanBaseExample();
        $plan3 = new PlanWithProductExample();

        $this->assertEquals(0, $product->getPlans()->count());

        $product->addPlan($plan1);
        $this->assertEquals(1, $product->getPlans()->count());

        $product->addPlan($plan2);
        $this->assertEquals(2, $product->getPlans()->count());

        $product->removePlan($plan2);
        $this->assertEquals(1, $product->getPlans()->count());

        $product->addPlan($plan3);
        $this->assertEquals(2, $product->getPlans()->count());
        $this->assertEquals($product, $plan3->getProduct());
    }
}
