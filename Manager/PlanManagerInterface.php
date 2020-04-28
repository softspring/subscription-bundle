<?php

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\CrudlBundle\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;

interface PlanManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @param string $plan
     * @return PlanInterface|null
     */
    public function convert(string $plan): ?PlanInterface;

//    /**
//     * @param PlanInterface $plan
//     */
//    public function syncFromPlatform(PlanInterface $plan): void;
//
//    /**
//     * @param PlanInterface $plan
//     */
//    public function syncToPlatform(PlanInterface $plan): void;

    /**
     *
     */
    public function syncAll(): void;

    /**
     * @return PlanInterface
     */
    public function createEntity();

    /**
     * @param PlanInterface $entity
     */
    public function saveEntity($entity): void;

    /**
     * @param PlanInterface $entity
     */
    public function deleteEntity($entity): void;
}