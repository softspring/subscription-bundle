<?php

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\ProductInterface;
use Softspring\SubscriptionBundle\Model\PlanProductRelationTrait as ModelPlanProductRelationTrait;

trait PlanProductRelationTrait
{
    use ModelPlanProductRelationTrait;

    /**
     * @var ProductInterface|null
     * @ORM\ManyToOne(targetEntity="Softspring\SubscriptionBundle\Model\ProductInterface", inversedBy="plans")
     */
    protected $product;
}