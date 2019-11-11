<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\SubscriptionBundle\Model\CustomerInterface;

class SourceManager implements SourceManagerInterface
{
    /**
     * @var ApiManagerInterface
     */
    protected $apiManager;

    /**
     * SourceManager constructor.
     *
     * @param ApiManagerInterface $apiManager
     */
    public function __construct(ApiManagerInterface $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * @inheritDoc
     */
    public function getSource(CustomerInterface $customer, string $sourceId): array
    {
        $stripeData = $this->apiManager->source()->getSource($customer, $sourceId);

        return $stripeData;
    }
}