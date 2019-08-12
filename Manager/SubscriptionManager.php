<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\Subscription\Model\ClientInterface;
use Softspring\Subscription\Model\PlanInterface;
use Softspring\Subscription\Model\SubscriptionInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ApiManagerInterface
     */
    protected $api;

    /**
     * SubscriptionManager constructor.
     * @param EntityManagerInterface $em
     * @param ApiManagerInterface $api
     */
    public function __construct(EntityManagerInterface $em, ApiManagerInterface $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    public function getClass(): string
    {
        return SubscriptionInterface::class;
    }

    public function getRepository(): EntityRepository
    {
        return $this->em->getRepository($this->getClass());
    }

    /**
     * @return SubscriptionInterface
     */
    public function createEntity()
    {
        $metadata = $this->em->getClassMetadata($this->getClass());
        $class = $metadata->getReflectionClass()->name;
        return new $class;
    }

    /**
     * @param SubscriptionInterface $entity
     */
    public function saveEntity($entity): void
    {
        if (!$entity instanceof SubscriptionInterface) {
            throw new \InvalidArgumentException(sprintf('$entity must be an instance of %s', SubscriptionInterface::class));
        }

        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function subscribe(ClientInterface $client, PlanInterface $plan): SubscriptionInterface
    {
        $subscription = $this->createEntity();

        $this->api->subscription()->subscribe($subscription, $client, $plan);

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function trial(ClientInterface $client, PlanInterface $plan): SubscriptionInterface
    {
        // TODO: Implement trial() method.
    }
}