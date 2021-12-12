<?php

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\ProductPlansRelationTrait as ModelProductPlansRelationTrait;

trait ProductPlansRelationTrait
{
    use ModelProductPlansRelationTrait;

    /**
     * @var Collection|PlanInterface[]
     * @ORM\OneToMany(targetEntity="Softspring\SubscriptionBundle\Model\PlanInterface", mappedBy="product")
     */
    protected $plans;
}