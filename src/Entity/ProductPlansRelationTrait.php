<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\ProductPlansRelationTrait as ModelProductPlansRelationTrait;

trait ProductPlansRelationTrait
{
    use ModelProductPlansRelationTrait;

    #[ORM\OneToMany(targetEntity: 'Softspring\SubscriptionBundle\Model\PlanInterface', mappedBy: 'product')]
    protected $plans;
}
