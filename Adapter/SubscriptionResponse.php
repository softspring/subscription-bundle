<?php

namespace Softspring\SubscriptionBundle\Adapter;

use Softspring\CustomerBundle\Adapter\AbstractResponse;
use Softspring\CustomerBundle\Exception\PlatformNotYetImplemented;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\CustomerBundle\PlatformInterface;

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
                            $this->status = SubscriptionInterface::STATUS_ACTIVE;
                            break;

                        case 'trialing':
                            $this->status = SubscriptionInterface::STATUS_TRIALING;
                            break;

                        default:
                            throw new SubscriptionException('Status not yet implemented');
                    }
                }

                if (!empty($platformResponse->current_period_start)) {
                    $this->currentPeriodStart = \DateTime::createFromFormat('U', $platformResponse->current_period_start) ?? null;
                }

                if (!empty($platformResponse->current_period_end)) {
                    $this->currentPeriodEnd = \DateTime::createFromFormat('U', $platformResponse->current_period_end) ?? null;
                }

                if (!empty($platformResponse->cancel_at)) {
                    $this->cancelAt = \DateTime::createFromFormat('U', $platformResponse->cancel_at) ?? null;
                }

                break;

            default:
                throw new PlatformNotYetImplemented();
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