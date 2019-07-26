<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\Subscription\Model\ProductInterface;

class ProductManager implements ProductManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * ProductManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getClass(): string
    {
        return ProductInterface::class;
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
        if (!$entity instanceof ProductInterface) {
            throw new \InvalidArgumentException(sprintf('$entity must be an instance of %s', ProductInterface::class));
        }

        $this->em->persist($entity);
        $this->em->flush();
    }
}