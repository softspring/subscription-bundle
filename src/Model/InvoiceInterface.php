<?php

namespace Softspring\SubscriptionBundle\Model;

interface InvoiceInterface
{
    public function getClient(): ?SubscriptionCustomerInterface;

    public function getIssueDate(): ?\DateTime;

    public function getNumber(): ?string;

    public function getTotal(): ?float;

    public function getTotalCurrency(): ?string;
}