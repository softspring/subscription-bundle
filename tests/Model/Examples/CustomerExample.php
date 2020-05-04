<?php

namespace Softspring\SubscriptionBundle\Tests\Model\Examples;

use Doctrine\Common\Collections\ArrayCollection;
use Softspring\CustomerBundle\Model\Customer;
use Softspring\CustomerBundle\Model\PlatformObjectTrait;
use Softspring\SubscriptionBundle\Model\CustomerSubscriptionsTrait;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;

class CustomerExample extends Customer implements SubscriptionCustomerInterface
{
    use CustomerSubscriptionsTrait;
    use PlatformObjectTrait;

    public function __construct()
    {
        parent::__construct();
        $this->subscriptions = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return 'test@example.com';
    }
}