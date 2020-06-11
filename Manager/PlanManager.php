<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Softspring\CrudlBundle\Manager\CrudlEntityManagerTrait;
use Softspring\PlatformBundle\Adapter\PlanAdapterInterface;
use Softspring\PlatformBundle\Manager\AdapterManagerInterface;
use Softspring\PlatformBundle\Model\PlatformObjectInterface;
use Softspring\PlatformBundle\Provider\PlatformProviderInterface;
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
     * @var PlatformProviderInterface|null
     */
    protected $platformProvider;

    /**
     * @var AdapterManagerInterface|null
     */
    protected $adapterManager;

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

    /**
     * @param AdapterManagerInterface|null $adapterManager
     */
    public function setAdapterManager(?AdapterManagerInterface $adapterManager): void
    {
        $this->adapterManager = $adapterManager;
    }

    /**
     * @param PlatformProviderInterface|null $platformProvider
     */
    public function setPlatformProvider(?PlatformProviderInterface $platformProvider): void
    {
        $this->platformProvider = $platformProvider;
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
        if (!$this->adapterManager) {
            return;
        }

        $dbPlans = $this->getRepository()->findAll();

        if (!$dbPlans instanceof Collection) {
            $dbPlans = new ArrayCollection($dbPlans);
        }

        $platformProvider = $this->platformProvider;

        /** @var PlanAdapterInterface $adapter */
        foreach ($this->adapterManager->getByType('plan') as $platform => $adapter) {
            $platformPlans = $adapter->list();
            $transformer = $adapter->getTransformer();

            foreach ($platformPlans as $platformPlan) {
                $dbPlan = $dbPlans->filter(function(PlanInterface $plan) use ($platform, $platformProvider, $platformPlan) {
                    /** @var PlatformObjectInterface $plan */

                    if ($platformPlan instanceof \Stripe\Plan) {
                        return $plan->getPlatformId() == $platformPlan->id && $platformProvider->getPlatform($plan) == $platform;
                    }

                    // not yet supported
                    return false;
                })->first();

                if (!$dbPlan) {
                    $dbPlan = $this->createEntity();
                }

                $transformer->reverseTransform($platformPlan, $dbPlan);

                $dbPlan->setPlatformWebhooked(true); // skip platform processing

                $this->saveEntity($dbPlan);

                $dbPlans->removeElement($dbPlan);
            }
        }

        /** @var PlanInterface|PlatformObjectInterface $dbPlan */
        foreach ($dbPlans as $dbPlan) {
            $dbPlan->setPlatformConflict(true);
            $this->saveEntity($dbPlan);
        }
    }
}