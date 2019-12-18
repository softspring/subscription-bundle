<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\AdminBundle\Manager\AdminEntityManagerTrait;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;

class SubscriptionTransitionManager implements SubscriptionTransitionManagerInterface
{
    use AdminEntityManagerTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * SubscriptionTransitionManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getTargetClass(): string
    {
        return SubscriptionTransitionInterface::class;
    }
}