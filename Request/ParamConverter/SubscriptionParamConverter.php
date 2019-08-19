<?php

namespace Softspring\SubscriptionBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Softspring\Subscription\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionParamConverter implements ParamConverterInterface
{
    /**
     * @var SubscriptionManagerInterface
     */
    protected $manager;

    /**
     * SubscriptionParamConverter constructor.
     * @param SubscriptionManagerInterface $manager
     */
    public function __construct(SubscriptionManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $query = $request->attributes->get($configuration->getName());
        $entity = $this->manager->getRepository()->findOneBy(['id' => $query]);
        $request->attributes->set($configuration->getName(), $entity);
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === SubscriptionInterface::class;
    }
}