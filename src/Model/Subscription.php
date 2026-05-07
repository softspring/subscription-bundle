<?php

namespace Softspring\SubscriptionBundle\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class Subscription implements SubscriptionInterface
{
    use SubscriptionStatusStringTrait;

    /**
     * @var SubscriptionCustomerInterface|null
     */
    protected $customer;

    /**
     * @var int|null
     */
    protected $status;

    /**
     * @var DateTime|null
     */
    protected $startDate;

    /**
     * @var DateTime|null
     */
    protected $endDate;

    /**
     * @var DateTime|null
     */
    protected $cancelScheduled;

    /**
     * @var Collection|SubscriptionTransitionInterface[]
     */
    protected $transitions;

    public function __construct()
    {
        $this->transitions = new ArrayCollection();
    }

    public function getCustomer(): ?SubscriptionCustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?SubscriptionCustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate ? DateTime::createFromFormat('U', $this->startDate) : null;
    }

    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate instanceof DateTime ? $startDate->format('U') : null;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate ? DateTime::createFromFormat('U', $this->endDate) : null;
    }

    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate instanceof DateTime ? $endDate->format('U') : null;
    }

    public function getCancelScheduled(): ?DateTime
    {
        return $this->cancelScheduled ? DateTime::createFromFormat('U', $this->cancelScheduled) : null;
    }

    public function setCancelScheduled(?DateTime $cancelScheduled): void
    {
        $this->cancelScheduled = $cancelScheduled instanceof DateTime ? $cancelScheduled->format('U') : null;
    }

    public function getTransitions(): Collection
    {
        return $this->transitions;
    }

    public function addTransition(SubscriptionTransitionInterface $transition): void
    {
        if (!$this->transitions->contains($transition)) {
            $this->transitions->add($transition);
            $transition->setSubscription($this);
        }
    }
}
