<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerTrait;
use Softspring\SubscriptionBundle\Model\SubscriptionTransitionInterface;

class SubscriptionTransitionManager implements SubscriptionTransitionManagerInterface
{
    use CrudlEntityManagerTrait;

    public function __construct(protected EntityManagerInterface $em)
    {
    }

    public function getTargetClass(): string
    {
        return SubscriptionTransitionInterface::class;
    }
}
