<?php

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Model\CustomerSubscriptionsTrait as ModelCustomerSubscriptionsTrait;

trait CustomerSubscriptionsTrait
{
    use ModelCustomerSubscriptionsTrait;

    /**
     * @var SubscriptionInterface[]|Collection
     * @ORM\OneToMany(targetEntity="Softspring\SubscriptionBundle\Model\SubscriptionInterface", mappedBy="customer", cascade={"persist"})
     */
    protected $subscriptions;
}