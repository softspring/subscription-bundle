<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\CrudlBundle\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;

interface SubscriptionTransitionManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return SubscriptionTransitionInterface
     */
    public function createEntity();

    /**
     * @param SubscriptionTransitionInterface $entity
     */
    public function saveEntity($entity): void;

    /**
     * @param SubscriptionTransitionInterface $entity
     */
    public function deleteEntity($entity): void;
}