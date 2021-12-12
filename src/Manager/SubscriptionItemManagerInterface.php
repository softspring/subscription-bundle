<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\CrudlBundle\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionItemInterface;

interface SubscriptionItemManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return SubscriptionItemInterface
     */
    public function createEntity();

    /**
     * @param SubscriptionItemInterface $entity
     */
    public function saveEntity($entity): void;

    /**
     * @param SubscriptionItemInterface $entity
     */
    public function deleteEntity($entity): void;
}