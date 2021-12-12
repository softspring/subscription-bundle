<?php

namespace Softspring\SubscriptionBundle\Model;

interface SubscriptionTransitionInterface
{
    /**
     * @param SubscriptionInterface|null $subscription
     */
    public function setSubscription(?SubscriptionInterface $subscription): void;

    /**
     * @return SubscriptionInterface|null
     */
    public function getSubscription(): ?SubscriptionInterface;

    /**
     * @param \DateTime|null $date
     */
    public function setDate(?\DateTime $date): void;

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime;

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @return string|null
     */
    public function getStatusString(): ?string;
}