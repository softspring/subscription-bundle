<?php

namespace Softspring\SubscriptionBundle\Tests\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\CustomerBundle\Model\PlatformObjectInterface;
use Softspring\SubscriptionBundle\Model\ProductInterface;
use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Tests\Model\Examples\CustomerExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\ProductBaseExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionMultiPlanExample;

class CustomerTest extends TestCase
{
    public function testInterfaces()
    {
        $this->assertInstanceOf(SubscriptionCustomerInterface::class, new CustomerExample());
        $this->assertInstanceOf(PlatformObjectInterface::class, new CustomerExample());
    }

    public function testCustomerSubscriptions()
    {
        $customer = new CustomerExample();

        $this->assertInstanceOf(Collection::class, $customer->getSubscriptions());
        $this->assertEquals(0, $customer->getSubscriptions()->count());

        $subscription = new SubscriptionMultiPlanExample();
        $customer->addSubscription($subscription);
        $this->assertEquals(1, $customer->getSubscriptions()->count());
        $this->assertEquals($customer, $subscription->getCustomer());

        $this->assertEquals(0, $customer->getActiveSubscriptions()->count());

        $subscription->setStatus(SubscriptionInterface::STATUS_ACTIVE);
        $this->assertEquals(1, $customer->getActiveSubscriptions()->count());

        $subscription = new SubscriptionMultiPlanExample();
        $subscription->setStatus(SubscriptionInterface::STATUS_TRIALING);
        $customer->addSubscription($subscription);

        $subscription = new SubscriptionMultiPlanExample();
        $subscription->setStatus(SubscriptionInterface::STATUS_UNPAID);
        $customer->addSubscription($subscription);

        $subscription = new SubscriptionMultiPlanExample();
        $subscription->setStatus(SubscriptionInterface::STATUS_CANCELED);
        $customer->addSubscription($subscription);

        $subscription = new SubscriptionMultiPlanExample();
        $subscription->setStatus(SubscriptionInterface::STATUS_SUSPENDED);
        $customer->addSubscription($subscription);

        $subscription = new SubscriptionMultiPlanExample();
        $subscription->setStatus(SubscriptionInterface::STATUS_EXPIRED);
        $customer->addSubscription($subscription);

        $this->assertEquals(5, $customer->getActiveSubscriptions()->count());
    }
}
