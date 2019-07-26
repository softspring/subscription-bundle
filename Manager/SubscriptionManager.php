<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\Subscription\Model\SubscriptionInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * SubscriptionManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getClass(): string
    {
        return SubscriptionInterface::class;
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
        if (!$entity instanceof SubscriptionInterface) {
            throw new \InvalidArgumentException(sprintf('$entity must be an instance of %s', SubscriptionInterface::class));
        }

        $this->em->persist($entity);
        $this->em->flush();
    }
}