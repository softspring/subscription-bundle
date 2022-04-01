<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\ProductInterface;

interface ProductManagerInterface extends CrudlEntityManagerInterface
{
    public function syncAll(): void;

    /**
     * @return ProductInterface
     */
    public function createEntity();

    /**
     * @param ProductInterface $entity
     */
    public function saveEntity($entity): void;

    /**
     * @param ProductInterface $entity
     */
    public function deleteEntity($entity): void;
}
