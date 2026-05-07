<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionItemInterface;

interface SubscriptionItemManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return SubscriptionItemInterface
     */
    public function createEntity(): object;

    /**
     * @param SubscriptionItemInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @param SubscriptionItemInterface $entity
     */
    public function deleteEntity(object $entity): void;
}
