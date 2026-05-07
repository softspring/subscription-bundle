<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\PlanProductRelationTrait as ModelPlanProductRelationTrait;

trait PlanProductRelationTrait
{
    use ModelPlanProductRelationTrait;

    #[ORM\ManyToOne(targetEntity: 'Softspring\SubscriptionBundle\Model\ProductInterface', inversedBy: 'plans')]
    protected $product;
}
