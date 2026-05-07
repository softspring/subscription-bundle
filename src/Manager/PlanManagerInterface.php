<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Manager;

use Softspring\Component\CrudlController\Manager\CrudlEntityManagerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;

interface PlanManagerInterface extends CrudlEntityManagerInterface
{
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

    public function syncAll(): void;

    /**
     * @return PlanInterface
     */
    public function createEntity(): object;

    /**
     * @param PlanInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @param PlanInterface $entity
     */
    public function deleteEntity(object $entity): void;
}
