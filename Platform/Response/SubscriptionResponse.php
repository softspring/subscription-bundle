<?php

namespace Softspring\SubscriptionBundle\Platform\Response;

use Softspring\CustomerBundle\Platform\Response\AbstractResponse;
use Softspring\CustomerBundle\Platform\Exception\PlatformNotYetImplemented;
use Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\CustomerBundle\Platform\PlatformInterface;

class SubscriptionResponse extends AbstractResponse
{
    /**
     * @var string|
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $status;

    /**
     * @var \DateTime|null
     */
    protected $currentPeriodStart;

    /**
     * @var \DateTime|null
     */
    protected $currentPeriodEnd;

    /**
     * @var \DateTime|null
     */
    protected $cancelAt;

    /**
     * SubscriptionResponse constructor.
     *
     * @param int   $platform
     * @param mixed $platformResponse
     *
     * @throws PlatformNotYetImplemented
     * @throws SubscriptionException
     */
    public function __construct(int $platform, $platformResponse)
    {
        parent::__construct($platform, $platformResponse);

        switch ($platform) {
            case PlatformInterface::PLATFORM_STRIPE:
                $this->id = $platformResponse->id;
                $this->testing = ! $platformResponse->livemode;

                if (!empty($platformResponse->status)) {
                    switch ($platformResponse->status) {
                        case 'active':
                        case 'incomplete':
                            $this->status = SubscriptionInterface::STATUS_ACTIVE;
                            break;

                        case 'trialing':
                            $this->status = SubscriptionInterface::STATUS_TRIALING;
                            break;

                        case 'unpaid':
                        case 'past_due':
                            $this->status = SubscriptionInterface::STATUS_UNPAID;
                            break;

                        case 'incomplete_expired':
                        case 'canceled':
                            $this->status = SubscriptionInterface::STATUS_EXPIRED;
                            break;

                        default:
                            throw new SubscriptionException($this->platform, 'status_not_yet_implemented', 'Status not yet implemented');
                    }
                }

                if (!empty($platformResponse->current_period_start)) {
                    $this->currentPeriodStart = \DateTime::createFromFormat('U', $platformResponse->current_period_start) ?? null;
                }

                if (!empty($platformResponse->current_period_end)) {
                    $this->currentPeriodEnd = \DateTime::createFromFormat('U', $platformResponse->current_period_end) ?? null;
                }

                if (!empty($platformResponse->cancel_at)) {
                    if (in_array($this->status, [SubscriptionInterface::STATUS_ACTIVE, SubscriptionInterface::STATUS_TRIALING])) {
                        $this->status = SubscriptionInterface::STATUS_CANCELED;
                    }

                    $this->cancelAt = \DateTime::createFromFormat('U', $platformResponse->cancel_at) ?? null;
                }

                break;

            default:
                throw new PlatformNotYetImplemented(-1, 'platform_not_yet_implemented');
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return \DateTime|null
     */
    public function getCurrentPeriodStart(): ?\DateTime
    {
        return $this->currentPeriodStart;
    }

    /**
     * @return \DateTime|null
     */
    public function getCurrentPeriodEnd(): ?\DateTime
    {
        return $this->currentPeriodEnd;
    }

    /**
     * @return \DateTime|null
     */
    public function getCancelAt(): ?\DateTime
    {
        return $this->cancelAt;
    }
}