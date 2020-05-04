<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\CustomerBundle\Model\PlatformObjectInterface;

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
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * @return int|null
     * @todo review if needed
     */
    public function getType(): ?int;

    /**
     * @return Collection|PlanInterface[]
     */
    public function getPlans(): Collection;
}