<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\AdminBundle\Manager\AdminEntityManagerTrait;
use Softspring\SubscriptionBundle\Model\CustomerInterface;

class CustomerManager implements CustomerManagerInterface
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
     * SubscriptionManager constructor.
     * @param EntityManagerInterface $em
     * @param ApiManagerInterface $api
     */
    public function __construct(EntityManagerInterface $em, ApiManagerInterface $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    public function getTargetClass(): string
    {
        return CustomerInterface::class;
    }

    public function createEntity()
    {
        $metadata = $this->em->getClassMetadata($this->getClass());
        $class = $metadata->getReflectionClass()->name;

        /** @var CustomerInterface $entity */
        $entity = new $class;
        $entity->setPlatform($this->api->platformId());

        return $entity;
    }

    public function createInPlatform(CustomerInterface $customer): void
    {
        if ($customer->getPlatformId()) {
            return;
        }

        $customer->setPlatform($this->api->platformId());
        $customer->setPlatformId($this->api->customer()->create($customer));

        // TODO $customer->setTestMode(true);

        $this->em->persist($customer);
        $this->em->flush();
    }

    public function addCard(CustomerInterface $customer, string $token, bool $setDefault = false): ?string
    {
        if (!$customer->getPlatformId()) {
            return null; // TODO exception
        }

        $this->api->customer()->addCard($customer, $token);

        return $this->api->customer()->getData($customer)->default_source;

        // TODO store default source
    }
}