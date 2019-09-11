<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

interface ProductInterface extends PlatformObjectInterface
{
    const TYPE_SERVICE = 1;

    const TYPE_GOOD_GENERAL = 10;
    const TYPE_GOOD_PHYSICAL = 11;
    const TYPE_GOOD_DIGITAL = 12;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return int|null
     */
    public function getType(): ?int;

    /**
     * @return Collection|PlanInterface[]
     */
    public function getPlans(): Collection;
}