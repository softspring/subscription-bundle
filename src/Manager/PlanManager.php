<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerTrait;
use Softspring\PlatformBundle\Adapter\PlanAdapterInterface;
use Softspring\PlatformBundle\Manager\AdapterManagerInterface;
use Softspring\PlatformBundle\Model\PlatformObjectInterface;
use Softspring\PlatformBundle\Provider\PlatformProviderInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Stripe\Plan;

class PlanManager implements PlanManagerInterface
{
    use CrudlEntityManagerTrait;

    protected ?PlatformProviderInterface $platformProvider = null;

    protected ?AdapterManagerInterface $adapterManager = null;

    public function __construct(
        protected EntityManagerInterface $em,
        protected ?ProductManagerInterface $productManager,
    ) {
    }

    public function setAdapterManager(?AdapterManagerInterface $adapterManager): void
    {
        $this->adapterManager = $adapterManager;
    }

    public function setPlatformProvider(?PlatformProviderInterface $platformProvider): void
    {
        $this->platformProvider = $platformProvider;
    }

    public function getTargetClass(): string
    {
        return PlanInterface::class;
    }

    public function convert(string $plan): ?PlanInterface
    {
        return $this->getRepository()->findOneBy(['id' => $plan]);
    }

    public function syncAll(): void
    {
        if (!$this->adapterManager instanceof AdapterManagerInterface) {
            return;
        }

        $dbPlans = $this->getRepository()->findAll();

        $dbPlans = new ArrayCollection($dbPlans);

        $platformProvider = $this->platformProvider;

        /** @var PlanAdapterInterface $adapter */
        foreach ($this->adapterManager->getByType('plan') as $platform => $adapter) {
            $platformPlans = $adapter->list();
            $transformer = $adapter->getTransformer();

            foreach ($platformPlans as $platformPlan) {
                $dbPlan = $dbPlans->filter(function (PlanInterface $plan) use ($platform, $platformProvider, $platformPlan): bool {
                    /* @var PlatformObjectInterface $plan */

                    if ($platformPlan instanceof Plan) {
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
