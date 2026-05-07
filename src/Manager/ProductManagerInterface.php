<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\ProductInterface;

interface ProductManagerInterface extends CrudlEntityManagerInterface
{
    public function syncAll(): void;

    /**
     * @return ProductInterface
     */
    public function createEntity(): object;

    /**
     * @param ProductInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @param ProductInterface $entity
     */
    public function deleteEntity(object $entity): void;
}
