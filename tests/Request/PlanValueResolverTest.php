<?php

namespace Softspring\SubscriptionBundle\Tests\Request;

use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Request\ValueResolver\PlanValueResolver;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PlanValueResolverTest extends TestCase
{
    public function testSkipsArgumentsThatAreAlreadyResolved(): void
    {
        $manager = $this->createMock(PlanManagerInterface::class);
        $resolver = new PlanValueResolver($manager);
        $request = new Request();
        $request->attributes->set('plan', new stdClass());

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    public function testSkipsUnsupportedArgumentTypes(): void
    {
        $manager = $this->createMock(PlanManagerInterface::class);
        $resolver = new PlanValueResolver($manager);
        $request = new Request();
        $request->attributes->set('plan', 'plan-42');

        $resolved = $resolver->resolve($request, new ArgumentMetadata('plan', stdClass::class, false, false, null));

        self::assertSame([], $resolved);
    }

    public function testReturnsEmptyResultWhenRouteParameterIsMissing(): void
    {
        $manager = $this->createMock(PlanManagerInterface::class);
        $resolver = new PlanValueResolver($manager);

        $resolved = $resolver->resolve(new Request(), $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    public function testResolvesPlanFromRouteAttribute(): void
    {
        $plan = $this->createMock(PlanInterface::class);
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['id' => 'plan-42'])
            ->willReturn($plan);

        $manager = $this->createMock(PlanManagerInterface::class);
        $manager->expects(self::once())
            ->method('getRepository')
            ->willReturn($repository);

        $resolver = new PlanValueResolver($manager);
        $request = new Request();
        $request->attributes->set('plan', 'plan-42');

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([$plan], $resolved);
    }

    public function testReturnsEmptyResultWhenRepositoryDoesNotReturnAPlan(): void
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['id' => 'plan-42'])
            ->willReturn(new stdClass());

        $manager = $this->createMock(PlanManagerInterface::class);
        $manager->expects(self::once())
            ->method('getRepository')
            ->willReturn($repository);

        $resolver = new PlanValueResolver($manager);
        $request = new Request();
        $request->attributes->set('plan', 'plan-42');

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    private function createArgumentMetadata(): ArgumentMetadata
    {
        return new ArgumentMetadata('plan', PlanInterface::class, false, false, null);
    }
}
