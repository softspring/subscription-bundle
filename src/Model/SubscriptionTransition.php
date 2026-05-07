<?php

namespace Softspring\SubscriptionBundle\Model;

use DateTime;

class SubscriptionTransition implements SubscriptionTransitionInterface
{
    use SubscriptionStatusStringTrait;

    /**
     * @var SubscriptionInterface|null
     */
    protected $subscription;

    /**
     * @var int|null
     */
    protected $date;

    /**
     * @var int|null
     */
    protected $status;

    public function getSubscription(): ?SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(?SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }

    public function getDate(): ?DateTime
    {
        return $this->date ? DateTime::createFromFormat('U', $this->date) : null;
    }

    public function setDate(?DateTime $date): void
    {
        $this->date = $date instanceof DateTime ? $date->format('U') : null;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }
}
