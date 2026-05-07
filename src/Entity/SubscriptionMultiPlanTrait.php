<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\SubscriptionMultiPlanTrait as SubscriptionMultiPlanTraitModel;

trait SubscriptionMultiPlanTrait
{
    use SubscriptionMultiPlanTraitModel;

    #[ORM\OneToMany(targetEntity: 'Softspring\SubscriptionBundle\Model\SubscriptionItemInterface', mappedBy: 'subscription', cascade: ['persist'], orphanRemoval: true)]
    protected $items;
}
