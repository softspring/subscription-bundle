<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\CustomerSubscriptionsTrait as ModelCustomerSubscriptionsTrait;

trait CustomerSubscriptionsTrait
{
    use ModelCustomerSubscriptionsTrait;

    #[ORM\OneToMany(targetEntity: 'Softspring\SubscriptionBundle\Model\SubscriptionInterface', mappedBy: 'customer', cascade: ['persist'])]
    protected $subscriptions;
}
