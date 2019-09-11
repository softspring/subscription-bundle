<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\SubscriptionBundle\Model\PlanInterface;

class PlanManager implements PlanManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * PlanManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getClass(): string
    {
        return PlanInterface::class;
    }

    public function getRepository(): EntityRepository
    {
        return $this->em->getRepository($this->getClass());
    }

    public function createEntity()
    {
        $metadata = $this->em->getClassMetadata($this->getClass());
        $class = $metadata->getReflectionClass()->name;
        return new $class;
    }

    public function saveEntity($entity): void
    {
        if (!$entity instanceof PlanInterface) {
            throw new \InvalidArgumentException(sprintf('$entity must be an instance of %s', PlanInterface::class));
        }

        $this->em->persist($entity);
        $this->em->flush();
    }

    public function convert(string $plan): ?PlanInterface
    {
        return $this->getRepository()->findOneBy(['platformId' => $plan]);
    }
}