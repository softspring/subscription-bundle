<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\CrudlBundle\Manager\CrudlEntityManagerTrait;
use Softspring\SubscriptionBundle\Model\PlanInterface;

class PlanManager implements PlanManagerInterface
{
    use CrudlEntityManagerTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ProductManagerInterface|null
     */
    protected $productManager;

    /**
     * PlanManager constructor.
     *
     * @param EntityManagerInterface       $em
     * @param ProductManagerInterface|null $productManager
     */
    public function __construct(EntityManagerInterface $em, ?ProductManagerInterface $productManager)
    {
        $this->em = $em;
        $this->productManager = $productManager;
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

        return $entity;
    }

    public function convert(string $plan): ?PlanInterface
    {
        return $this->getRepository()->findOneBy(['id' => $plan]);
    }

    /**
     *
     */
    public function syncAll(): void
    {
//        $dbPlans = new ArrayCollection($this->getRepository()->findAll());
//        // $platformPlans = $this->api->get('plan')->list();
//
//        /** @var PlanResponse $platformPlan */
//        foreach ($platformPlans as $platformPlan) {
//            $dbPlan = $dbPlans->filter(function(PlanInterface $plan) use ($platformPlan) {
//                return $plan->getPlatformId() == $platformPlan->getId();
//            })->first();
//
//            if (!$dbPlan) {
//                // not found, create in db
//                $dbPlan = $this->createEntity();
//                // $dbPlan->setPlatform($this->api->platformId());
//                $dbPlan->setPlatformId($platformPlan->getId());
//            }
//
//            $dbPlan->setName($platformPlan->getName());
//            $dbPlan->setActive($platformPlan->getActive());
//            $dbPlan->setAmount($platformPlan->getAmount());
//            $dbPlan->setCurrency($platformPlan->getCurrency());
//            $dbPlan->setTestMode($platformPlan->isTesting());
//            $dbPlan->setInterval($platformPlan->getInterval());
//            $dbPlan->setPlatformData($platformPlan->getPlatformNativeArray());
//            $dbPlan->setPlatformLastSync(new \DateTime('now'));
//            $dbPlan->setPlatformConflict(false);
//
//            if ($dbPlan instanceof PlanHasProductInterface) {
//                if ($this->productManager && $platformPlan->getProductId()) {
//                    $dbProduct = $this->productManager->getRepository()->findOneByPlatformId($platformPlan->getProductId());
//                    $dbPlan->setProduct($dbProduct);
//                } else {
//                    $dbPlan->setProduct(null);
//                }
//            }
//
//            $this->saveEntity($dbPlan);
//
//            $dbPlans->removeElement($dbPlan);
//        }
//
//        /** @var PlanInterface $dbPlan */
//        foreach ($dbPlans as $dbPlan) {
//            $dbPlan->setPlatformConflict(true);
//            $this->saveEntity($dbPlan);
//        }
    }
}