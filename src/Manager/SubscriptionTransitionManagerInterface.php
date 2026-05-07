<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;

interface SubscriptionTransitionManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return SubscriptionTransitionInterface
     */
    public function createEntity(): object;

    /**
     * @param SubscriptionTransitionInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @param SubscriptionTransitionInterface $entity
     */
    public function deleteEntity(object $entity): void;
}
