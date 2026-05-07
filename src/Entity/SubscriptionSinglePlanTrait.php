<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\SubscriptionSinglePlanTrait as SubscriptionSinglePlanTraitModel;

trait SubscriptionSinglePlanTrait
{
    use SubscriptionSinglePlanTraitModel;

    #[ORM\ManyToOne(targetEntity: 'Softspring\SubscriptionBundle\Model\PlanInterface')]
    #[ORM\JoinColumn(name: 'plan_id')]
    protected $plan;
}
