<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Subscription implements SubscriptionInterface
{
    use PlatformObjectTrait;
    use SubscriptionStatusStringTrait;

    /**
     * @var ClientInterface|null
     */
    protected $client;

    /**
     * @var ProductInterface|null
     */
    protected $product;

    /**
     * @var PlanInterface|null
     */
    protected $plan;

    /**
     * @var int|null
     */
    protected $status;

    /**
     * @var \DateTime|null
     */
    protected $startDate;

    /**
     * @var \DateTime|null
     */
    protected $endDate;

    /**
     * @var \DateTime|null
     */
    protected $cancelScheduled;

    /**
     * @var Collection|SubscriptionTransitionInterface[]
     */
    protected $transitions;

    /**
     * Subscription constructor.
     */
    public function __construct()
    {
        $this->transitions = new ArrayCollection();
    }

    /**
     * @return ClientInterface|null
     */
    public function getClient(): ?ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface|null $client
     */
    public function setClient(?ClientInterface $client): void
    {
        $this->client = $client;
    }

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    /**
     * @return PlanInterface|null
     */
    public function getPlan(): ?PlanInterface
    {
        return $this->plan;
    }

    /**
     * @param PlanInterface|null $plan
     */
    public function setPlan(?PlanInterface $plan): void
    {
        $this->setProduct($plan->getProduct());
        $this->plan = $plan;
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

    /**
     * @return \DateTime|null
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate ? \DateTime::createFromFormat('U', $this->startDate) : null;
    }

    /**
     * @param \DateTime|null $startDate
     */
    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate instanceof \DateTime ? $startDate->format('U') : null;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndDate(): ?\DateTime
    {
        return $this->endDate ? \DateTime::createFromFormat('U', $this->endDate) : null;
    }

    /**
     * @param \DateTime|null $endDate
     */
    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate instanceof \DateTime ? $endDate->format('U') : null;
    }

    /**
     * @return \DateTime|null
     */
    public function getCancelScheduled(): ?\DateTime
    {
        return $this->cancelScheduled ? \DateTime::createFromFormat('U', $this->cancelScheduled) : null;
    }

    /**
     * @param \DateTime|null $cancelScheduled
     */
    public function setCancelScheduled(?\DateTime $cancelScheduled): void
    {
        $this->cancelScheduled = $cancelScheduled instanceof \DateTime ? $cancelScheduled->format('U') : null;
    }

    /**
     * @inheritDoc
     */
    public function getTransitions(): Collection
    {
        return $this->transitions;
    }

    /**
     * @inheritDoc
     */
    public function addTransition(SubscriptionTransitionInterface $transition): void
    {
        if (!$this->transitions->contains($transition)) {
            $this->transitions->add($transition);
        }
    }
}