<?php

namespace Softspring\SubscriptionBundle\Tests\Request;

use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Request\ValueResolver\SubscriptionValueResolver;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class SubscriptionValueResolverTest extends TestCase
{
    public function testSkipsArgumentsThatAreAlreadyResolved(): void
    {
        $manager = $this->createMock(SubscriptionManagerInterface::class);
        $resolver = new SubscriptionValueResolver($manager);
        $request = new Request();
        $request->attributes->set('subscription', new stdClass());

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    public function testSkipsUnsupportedArgumentTypes(): void
    {
        $manager = $this->createMock(SubscriptionManagerInterface::class);
        $resolver = new SubscriptionValueResolver($manager);
        $request = new Request();
        $request->attributes->set('subscription', 'subscription-42');

        $resolved = $resolver->resolve($request, new ArgumentMetadata('subscription', stdClass::class, false, false, null));

        self::assertSame([], $resolved);
    }

    public function testReturnsEmptyResultWhenRouteParameterIsMissing(): void
    {
        $manager = $this->createMock(SubscriptionManagerInterface::class);
        $resolver = new SubscriptionValueResolver($manager);

        $resolved = $resolver->resolve(new Request(), $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    public function testResolvesSubscriptionFromRouteAttribute(): void
    {
        $subscription = $this->createMock(SubscriptionInterface::class);
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['id' => 'subscription-42'])
            ->willReturn($subscription);

        $manager = $this->createMock(SubscriptionManagerInterface::class);
        $manager->expects(self::once())
            ->method('getRepository')
            ->willReturn($repository);

        $resolver = new SubscriptionValueResolver($manager);
        $request = new Request();
        $request->attributes->set('subscription', 'subscription-42');

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([$subscription], $resolved);
    }

    public function testReturnsEmptyResultWhenRepositoryDoesNotReturnASubscription(): void
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['id' => 'subscription-42'])
            ->willReturn(new stdClass());

        $manager = $this->createMock(SubscriptionManagerInterface::class);
        $manager->expects(self::once())
            ->method('getRepository')
            ->willReturn($repository);

        $resolver = new SubscriptionValueResolver($manager);
        $request = new Request();
        $request->attributes->set('subscription', 'subscription-42');

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    private function createArgumentMetadata(): ArgumentMetadata
    {
        return new ArgumentMetadata('subscription', SubscriptionInterface::class, false, false, null);
    }
}
