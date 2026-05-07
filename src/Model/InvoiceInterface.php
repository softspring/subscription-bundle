<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Model;

use DateTime;

interface InvoiceInterface
{
    public function getClient(): ?SubscriptionCustomerInterface;

    public function getIssueDate(): ?DateTime;

    public function getNumber(): ?string;

    public function getTotal(): ?float;

    public function getTotalCurrency(): ?string;
}
