<?php

namespace Softspring\SubscriptionBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionMultiPlanExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionTransitionExample;

class SubscriptionTransitionTest extends TestCase
{
    public function testInterfaces()
    {
        $this->assertInstanceOf(SubscriptionTransitionInterface::class, new SubscriptionTransitionExample());
    }

    public function testBasePlanGetterAndSetters()
    {
        $transition = new SubscriptionTransitionExample();

        $this->assertNull($transition->getSubscription());
        $this->assertNull($transition->getStatus());
        $this->assertNull($transition->getDate());
        $this->assertNull($transition->getStatusString());

        $subscription = new SubscriptionMultiPlanExample();
        $transition->setSubscription($subscription);
        $this->assertEquals($subscription, $transition->getSubscription());

        $date = new \DateTime('now');
        $transition->setDate($date);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $transition->getDate()->format('Y-m-d H:i:s'));

        $transition->setStatus(SubscriptionInterface::STATUS_EXPIRED);
        $this->assertEquals(SubscriptionInterface::STATUS_EXPIRED, $transition->getStatus());
    }

    public function testStatusNames()
    {
        $transition = new SubscriptionTransitionExample();

        $transition->setStatus(SubscriptionInterface::STATUS_TRIALING);
        $this->assertEquals('trialing', $transition->getStatusString());

        $transition->setStatus(SubscriptionInterface::STATUS_ACTIVE);
        $this->assertEquals('active', $transition->getStatusString());

        $transition->setStatus(SubscriptionInterface::STATUS_UNPAID);
        $this->assertEquals('unpaid', $transition->getStatusString());

        $transition->setStatus(SubscriptionInterface::STATUS_CANCELED);
        $this->assertEquals('canceled', $transition->getStatusString());

        $transition->setStatus(SubscriptionInterface::STATUS_SUSPENDED);
        $this->assertEquals('suspended', $transition->getStatusString());

        $transition->setStatus(SubscriptionInterface::STATUS_EXPIRED);
        $this->assertEquals('expired', $transition->getStatusString());
    }
}
