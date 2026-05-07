<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Request\ValueResolver;

use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PlanValueResolver implements ValueResolverInterface
{
    public function __construct(protected PlanManagerInterface $manager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (\is_object($request->attributes->get($argument->getName()))) {
            return [];
        }

        if (PlanInterface::class !== $argument->getType()) {
            return [];
        }

        $query = $request->attributes->get($argument->getName());

        if (null === $query || '' === $query) {
            return [];
        }

        $entity = $this->manager->getRepository()->findOneBy(['id' => $query]);

        if (!$entity instanceof PlanInterface) {
            return [];
        }

        return [$entity];
    }
}
