<?php

namespace Softspring\SubscriptionBundle\Controller\Admin;

use Softspring\CoreBundle\Controller\AbstractController;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class PlanController extends AbstractController
{
    /**
     * @var PlanManagerInterface
     */
    protected $planManager;

    /**
     * PlanController constructor.
     *
     * @param PlanManagerInterface $planManager
     */
    public function __construct(PlanManagerInterface $planManager)
    {
        $this->planManager = $planManager;
    }

    /**
     * @return Response
     *
     * TODO restrict by permission
     */
    public function syncAll(): Response
    {
        $this->planManager->syncAll();

        return $this->redirectToRoute('sfs_subscription_admin_plans_list');
    }
}