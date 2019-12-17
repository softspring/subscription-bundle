<?php

namespace Softspring\SubscriptionBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\CustomerBundle\Model\PlatformObjectInterface;

interface SubscriptionInterface extends PlatformObjectInterface
{
    /**
     * TODO MAP NEXT STATUSES:
     *      Paypal: APPROVAL_PENDING
     *      Stripe: incomplete, incomplete_expired
     * @see https://developer.paypal.com/docs/api/subscriptions/v1/#subscriptions-get-response
     * @see https://stripe.com/docs/api/subscriptions/object#subscription_object-status
     */

    /**
     * Subscription is in trial period
     * Stripe: trialing
     * Paypal:
     */
    const STATUS_TRIALING = 10;

    /**
     * Subscription is active and up to date
     * Stripe: active
     * Paypal: ACTIVE, APPROVED
     */
    const STATUS_ACTIVE = 15;

    /**
     * Subscription has pending payments
     * Stripe: unpaid, past_due
     * Paypal:
     */
    const STATUS_UNPAID = 18;

    /**
     * Subscription is cancelled, but still active
     * Stripe: canceled
     * Paypal: CANCELLED
     */
    const STATUS_CANCELED = 21;

    /**
     * Subscription is suspended temporary
     * Stripe:
     * Paypal: SUSPENDED
     */
    const STATUS_SUSPENDED = 30;

    /**
     * Subscription is expired, and no active anymore
     * Stripe:
     * Paypal: EXPIRED
     */
    const STATUS_EXPIRED = 35;

    public function getCustomer(): ?SubscriptionCustomerInterface;

    public function setCustomer(?SubscriptionCustomerInterface $customer): void;

    public function getPlan(): ?PlanInterface;

    public function setPlan(?PlanInterface $plan): void;

    public function getStatus(): ?int;

    public function setStatus(?int $status): void;

    public function getStatusString(): ?string;

    public function getStartDate(): ?\DateTime;

    public function setStartDate(?\DateTime $startDate): void;

    public function getEndDate(): ?\DateTime;

    public function setEndDate(?\DateTime $endDate): void;

    public function getCancelScheduled(): ?\DateTime;

    public function setCancelScheduled(?\DateTime $endDate): void;

    /**
     * @return Collection|SubscriptionTransitionInterface[]
     */
    public function getTransitions(): Collection;

    /**
     * @param SubscriptionTransitionInterface $transition
     */
    public function addTransition(SubscriptionTransitionInterface $transition): void;
}