<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Softspring\AdminBundle\Manager\AdminEntityManagerTrait;
use Softspring\SubscriptionBundle\Adapter\PlanResponse;
use Softspring\SubscriptionBundle\Model\PlanInterface;

class PlanManager implements PlanManagerInterface
{
    use AdminEntityManagerTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ApiManagerInterface
     */
    protected $api;

    /**
     * PlanManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ApiManagerInterface    $api
     */
    public function __construct(EntityManagerInterface $em, ApiManagerInterface $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    public function getTargetClass(): string
    {
        return PlanInterface::class;
    }

    public function createEntity()
    {
        $metadata = $this->em->getClassMetadata($this->getClass());
        $class = $metadata->getReflectionClass()->name;

        /** @var PlanInterface $entity */
        $entity = new $class;
        $entity->setPlatform($this->api->platformId());

        return $entity;
    }

    public function convert(string $plan): ?PlanInterface
    {
        return $this->getRepository()->findOneBy(['platformId' => $plan]);
    }

//    /**
//     * @param PlanInterface $plan
//     */
//    public function syncFromPlatform(PlanInterface $plan): void
//    {
//
//    }
//
//    /**
//     * @param PlanInterface $plan
//     */
//    public function syncToPlatform(PlanInterface $plan): void
//    {
//
//    }

    /**
     *
     */
    public function syncAll(): void
    {
        $dbPlans = new ArrayCollection($this->getRepository()->findAll());
        $platformPlans = $this->api->plan()->list();

        /** @var PlanResponse $platformPlan */
        foreach ($platformPlans as $platformPlan) {
            $dbPlan = $dbPlans->filter(function(PlanInterface $plan) use ($platformPlan) {
                return $plan->getPlatformId() == $platformPlan->getId();
            })->first();

            if (!$dbPlan) {
                // not found, create in db
                $dbPlan = $this->createEntity();
                $dbPlan->setPlatform($this->api->platformId());
                $dbPlan->setPlatformId($platformPlan->getId());
            }

            $dbPlan->setName($platformPlan->getName());
            $dbPlan->setActive($platformPlan->getActive());
            $dbPlan->setAmount($platformPlan->getAmount());
            $dbPlan->setCurrency($platformPlan->getCurrency());
            $dbPlan->setTestMode($platformPlan->isTesting());
            $dbPlan->setInterval($platformPlan->getInterval());
            $dbPlan->setPlatformData($platformPlan->getPlatformNativeArray());
            $dbPlan->setPlatformLastSync(new \DateTime('now'));
            $dbPlan->setPlatformConflict(false);
            $this->saveEntity($dbPlan);

            $dbPlans->removeElement($dbPlan);
        }

        /** @var PlanInterface $dbPlan */
        foreach ($dbPlans as $dbPlan) {
            $dbPlan->setPlatformConflict(true);
            $this->saveEntity($dbPlan);
        }
    }
}