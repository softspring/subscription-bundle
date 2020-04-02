<?php

namespace Softspring\SubscriptionBundle\Platform\Response;

use Softspring\CustomerBundle\Platform\Response\AbstractResponse;
use Softspring\CustomerBundle\Platform\Exception\PlatformNotYetImplemented;
use Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException;
use Softspring\CustomerBundle\Platform\PlatformInterface;

class PlanResponse extends AbstractResponse
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var bool|null
     */
    protected $active;

    /**
     * @var float|null
     */
    protected $amount;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var int|null
     */
    protected $interval;

    /**
     * @var string|null
     */
    protected $productId;

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

                if (isset($platformResponse->nickname)) {
                    $this->name = $platformResponse->nickname;
                }

                if (isset($platformResponse->active)) {
                    $this->active = $platformResponse->active;
                }

                if (isset($platformResponse->amount)) {
                    $this->amount = $platformResponse->amount/100;
                }

                if (isset($platformResponse->currency)) {
                    $this->currency = $platformResponse->currency;
                }

                if (isset($platformResponse->interval) && isset($platformResponse->interval_count)) {
                    $intervalMapping = [
                        'year' => 365,
                        'month' => 30,
                        'week' => 7,
                        'day' => 1,
                    ];
                    $this->interval = $intervalMapping[$platformResponse->interval] * $platformResponse->interval_count;
                }

                if (isset($platformResponse->product)) {
                    $this->productId = $platformResponse->product;
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @return int|null
     */
    public function getInterval(): ?int
    {
        return $this->interval;
    }

    /**
     * @return string|null
     */
    public function getProductId(): ?string
    {
        return $this->productId;
    }
}