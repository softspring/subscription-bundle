<?php

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\SubscriptionItemInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionMultiPlanTrait as SubscriptionMultiPlanTraitModel;

trait SubscriptionMultiPlanTrait
{
    use SubscriptionMultiPlanTraitModel;

    /**
     * @var Collection|SubscriptionItemInterface[]
     * @ORM\OneToMany(targetEntity="Softspring\SubscriptionBundle\Model\SubscriptionItemInterface", mappedBy="subscription", cascade={"persist"}, orphanRemoval=true)
     */
    protected $items;
}