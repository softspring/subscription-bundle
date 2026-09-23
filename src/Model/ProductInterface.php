<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;

interface ProductInterface
{
    public const TYPE_SERVICE = 1;

    public const TYPE_GOOD_GENERAL = 10;
    public const TYPE_GOOD_PHYSICAL = 11;
    public const TYPE_GOOD_DIGITAL = 12;

    public function getName(): ?string;

    public function setName(?string $name): void;

    /**
     * @todo review if needed
     */
    public function getType(): ?int;

    public function getPlans(): Collection;
}
