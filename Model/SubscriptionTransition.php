<?php

namespace Softspring\SubscriptionBundle\Model;

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

    /**
     * @return SubscriptionInterface|null
     */
    public function getSubscription(): ?SubscriptionInterface
    {
        return $this->subscription;
    }

    /**
     * @param SubscriptionInterface|null $subscription
     */
    public function setSubscription(?SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date ? \DateTime::createFromFormat('U', $this->date) : null;
    }

    /**
     * @param \DateTime|null $date
     */
    public function setDate(?\DateTime $date): void
    {
        $this->date = $date instanceof \DateTime ? $date->format('U') : null;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }
}