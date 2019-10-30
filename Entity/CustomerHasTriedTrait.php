<?php

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait CustomerHasTriedTrait
{
    /**
     * @var bool
     * @ORM\Column(name="has_tried", type="boolean", nullable=false, options={"default"=false})
     */
    protected $tried = false;

    /**
     * @return bool
     */
    public function hasTried(): bool
    {
        return $this->tried;
    }

    /**
     * @param bool $tried
     */
    public function setTried(bool $tried): void
    {
        $this->tried = $tried;
    }
}