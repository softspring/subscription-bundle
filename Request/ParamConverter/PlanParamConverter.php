<?php

namespace Softspring\SubscriptionBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PlanParamConverter implements ParamConverterInterface
{
    /**
     * @var PlanManagerInterface
     */
    protected $manager;

    /**
     * PlanParamConverter constructor.
     * @param PlanManagerInterface $manager
     */
    public function __construct(PlanManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $query = $request->attributes->get($configuration->getName());
        $entity = $this->manager->getRepository()->findOneBy(['platformId' => $query]);
        $request->attributes->set($configuration->getName(), $entity);
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === PlanInterface::class;
    }
}