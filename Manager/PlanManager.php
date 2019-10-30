<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\AdminBundle\Manager\AdminEntityManagerTrait;
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
}