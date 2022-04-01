<?php

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerTrait;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;

class SubscriptionTransitionManager implements SubscriptionTransitionManagerInterface
{
    use CrudlEntityManagerTrait;

    protected EntityManagerInterface $em;

    /**
     * SubscriptionTransitionManager constructor.
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
