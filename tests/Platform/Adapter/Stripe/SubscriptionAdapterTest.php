<?php

namespace Softspring\SubscriptionBundle\Tests\Platform\Adapter\Stripe;

use PHPUnit\Framework\MockObject\MockObject;
use Softspring\CustomerBundle\Platform\Exception\NotFoundInPlatform;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Platform\Adapter\Stripe\SubscriptionAdapter;
use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\Tests\Model\Examples\CustomerExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\PlanBaseExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionItemBaseExample;
use Softspring\SubscriptionBundle\Tests\Model\Examples\SubscriptionMultiPlanExample;
use Stripe\Exception\InvalidRequestException;
use Stripe\Plan;
use Stripe\Subscription;
use Stripe\SubscriptionItem;

class SubscriptionAdapterTest extends TestCase
{
    /**
     * @var SubscriptionAdapter
     */
    protected $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->getMockBuilder(SubscriptionAdapter::class)
            ->setConstructorArgs(['sk_test_xxx', null, null])
            ->onlyMethods(['initStripe', 'stripeClientCreate', 'stripeClientRetrieve'])
            ->getMock();
    }

    public function testGetExisting()
    {
        $subscription = new SubscriptionMultiPlanExample();
        $subscription->setPlatformId('sub_test');

        $this->adapter->method('stripeClientRetrieve')->will($this->returnValue($this->createStripeSubscriptionObject([
            'id' => 'sub_test',
            'livemode' => false,
            'created' => ($created = new \DateTime('now'))->format('U'),
            'items' => [],
            'current_period_start' => ($periodStart = new \DateTime('-1 month'))->format('U'),
            'current_period_end' => ($periodEnd = new \DateTime('now'))->format('U'),
            'status' => 'active',
            'cancel_at' => null,
        ])));

        $this->adapter->get($subscription);
        $this->assertEquals('sub_test', $subscription->getPlatformId());
        $this->assertEquals(true, $subscription->isTestMode());
        $this->assertEquals(false, $subscription->isPlatformConflict());
        $this->assertEquals($created->format('Y-m-d H:i:s'), $subscription->getPlatformLastSync()->format('Y-m-d H:i:s'));
        $this->assertEquals($periodStart->format('Y-m-d H:i:s'), $subscription->getStartDate()->format('Y-m-d H:i:s'));
        $this->assertEquals($periodEnd->format('Y-m-d H:i:s'), $subscription->getEndDate()->format('Y-m-d H:i:s'));
        $this->assertEquals(SubscriptionInterface::STATUS_ACTIVE, $subscription->getStatus());
        $this->assertEquals(null, $subscription->getCancelScheduled());
    }

    public function testGetMissing()
    {
        $this->expectException(NotFoundInPlatform::class);

        $subscription = new SubscriptionMultiPlanExample();
        $subscription->setPlatformId('sub_test_not_existing');

        $e = new InvalidRequestException();
        $e->setStripeCode('resource_missing');
        $this->adapter->method('stripeClientRetrieve')->will($this->throwException($e));

        $this->adapter->get($subscription);
    }

    public function testCreate()
    {
        $plan = new PlanBaseExample();
        $plan->setPlatformId('plan_test');

        $customer = new CustomerExample();
        $customer->setPlatformId('cus_test');

        $subscription = new SubscriptionMultiPlanExample();
        $subscription->setCustomer($customer);

        $item = new SubscriptionItemBaseExample();
        $item->setPlan($plan);
        $subscription->addItem($item);

        $this->adapter->method('stripeClientCreate')->will($this->returnValue($this->createStripeSubscriptionObject([
            'id' => 'sub_created',
            'livemode' => false,
            'created' => ($created = new \DateTime('now'))->format('U'),
            'items' => [
                $this->createStripeSubscriptionItemObject([
                    'id' => 'si_test',
                    'livemode' => false,
                    'created' => ($created = new \DateTime('now'))->format('U'),
                    'plan' => $this->createStripePlanObject([
                        'id' => 'plan_test',
                    ]),
                    'quantity' => 1,
                ]),
            ],
            'current_period_start' => ($periodStart = new \DateTime('now'))->format('U'),
            'current_period_end' => ($periodEnd = new \DateTime('+1 month'))->format('U'),
            'status' => 'active',
            'cancel_at' => null,
        ])));

        $this->adapter->create($subscription);
        $this->assertEquals('sub_created', $subscription->getPlatformId());
        $this->assertEquals(true, $subscription->isTestMode());
        $this->assertEquals(false, $subscription->isPlatformConflict());
        $this->assertEquals($created->format('Y-m-d H:i:s'), $subscription->getPlatformLastSync()->format('Y-m-d H:i:s'));
        $this->assertEquals($periodStart->format('Y-m-d H:i:s'), $subscription->getStartDate()->format('Y-m-d H:i:s'));
        $this->assertEquals($periodEnd->format('Y-m-d H:i:s'), $subscription->getEndDate()->format('Y-m-d H:i:s'));
        $this->assertEquals(SubscriptionInterface::STATUS_ACTIVE, $subscription->getStatus());
        $this->assertEquals(null, $subscription->getCancelScheduled());
    }

    /**
     * @param array $params
     *
     * @return Subscription|MockObject
     */
    protected function createStripeSubscriptionObject(array $params)
    {
        $stripeSubscription = $this->getMockBuilder(Subscription::class)->getMock();

        $stripeSubscription->method('__get')->willReturnCallback(function ($param) use ($params) {
            return $params[$param];
        });

        return $stripeSubscription;
    }

    /**
     * @param array $params
     *
     * @return SubscriptionItem|MockObject
     */
    protected function createStripeSubscriptionItemObject(array $params)
    {
        $stripeSubscriptionItem = $this->getMockBuilder(SubscriptionItem::class)->getMock();

        $stripeSubscriptionItem->method('__get')->willReturnCallback(function ($param) use ($params) {
            return $params[$param];
        });

        return $stripeSubscriptionItem;
    }

    /**
     * @param array $params
     *
     * @return Plan|MockObject
     */
    protected function createStripePlanObject(array $params)
    {
        $stripePlan = $this->getMockBuilder(Plan::class)->getMock();

        $stripePlan->method('__get')->willReturnCallback(function ($param) use ($params) {
            return $params[$param];
        });

        return $stripePlan;
    }
}
