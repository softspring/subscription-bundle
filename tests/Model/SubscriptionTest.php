<?php

namespace Softspring\SubscriptionBundle\Tests\Model;

use Softspring\CustomerBundle\Model\PlatformObjectInterface;
use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionMultiPlanInterface;
use Softspring\SubscriptionBundle\Tests\Model\Examples\CustomerExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\PlanBaseExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionItemBaseExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionMultiPlanExample;

class SubscriptionTest extends TestCase
{
    public function testInterfaces()
    {
        $this->assertInstanceOf(SubscriptionInterface::class, new SubscriptionMultiPlanExample());
        $this->assertInstanceOf(PlatformObjectInterface::class, new SubscriptionMultiPlanExample());
        $this->assertInstanceOf(SubscriptionMultiPlanInterface::class, new SubscriptionMultiPlanExample());
    }

    public function testMultiPlanGetterAndSetters()
    {
        $subscription = new SubscriptionMultiPlanExample();

        $this->assertNull($subscription->getCustomer());
        $this->assertNull($subscription->getStatus());
        $this->assertNull($subscription->getStartDate());
        $this->assertNull($subscription->getEndDate());
        $this->assertNull($subscription->getCancelScheduled());

        $customer = new CustomerExample();
        $subscription->setCustomer($customer);
        $this->assertEquals($customer, $subscription->getCustomer());

        $subscription->setStatus(SubscriptionInterface::STATUS_ACTIVE);
        $this->assertEquals(SubscriptionInterface::STATUS_ACTIVE, $subscription->getStatus());

        $startDate = new \DateTime('now');
        $subscription->setStartDate($startDate);
        $this->assertEquals($startDate->format('Y-m-d H:i:s'), $subscription->getStartDate()->format('Y-m-d H:i:s'));

        $endDate = new \DateTime('+1 month');
        $subscription->setEndDate($endDate);
        $this->assertEquals($endDate->format('Y-m-d H:i:s'), $subscription->getEndDate()->format('Y-m-d H:i:s'));

        $cancelScheduledDate = new \DateTime('+1 month');
        $subscription->setCancelScheduled($cancelScheduledDate);
        $this->assertEquals($cancelScheduledDate->format('Y-m-d H:i:s'), $subscription->getCancelScheduled()->format('Y-m-d H:i:s'));

        //$this->assertNull($subscription->getTransitions()); todo transitions go to interface/trait elements
    }

    public function testMultiPlan()
    {
        $subscription = new SubscriptionMultiPlanExample();

        $this->assertEquals(0, $subscription->getItems()->count());

        $plan1 = new PlanBaseExample();
        $plan2 = new PlanBaseExample();

        $item = new SubscriptionItemBaseExample();
        $item->setPlan($plan1);
        $subscription->addItem($item);
        $this->assertEquals($subscription, $item->getSubscription());
        $this->assertEquals(1, $subscription->getItems()->count());

        $item = new SubscriptionItemBaseExample();
        $item->setPlan($plan2);
        $subscription->addItem($item);
        $this->assertEquals($subscription, $item->getSubscription());
        $this->assertEquals(2, $subscription->getItems()->count());

        $this->assertEquals(1, $subscription->getItemsForPlan($plan1)->count());
        $this->assertEquals(1, $subscription->getItemsForPlan($plan2)->count());

        $subscription->removeItem($item);
        $this->assertEquals(1, $subscription->getItems()->count());

        $this->assertEquals(1, $subscription->getItemsForPlan($plan1)->count());
        $this->assertEquals(0, $subscription->getItemsForPlan($plan2)->count());
    }
}