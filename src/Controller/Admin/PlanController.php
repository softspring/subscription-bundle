<?php

namespace Softspring\SubscriptionBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PlanController extends AbstractController
{
    /**
     * @var PlanManagerInterface
     */
    protected $planManager;

    /**
     * PlanController constructor.
     */
    public function __construct(PlanManagerInterface $planManager)
    {
        $this->planManager = $planManager;
    }

    /**
     * @Security(expression="is_granted('ROLE_SUBSCRIPTION_ADMIN_PLANS_SYNC_ALL')")
     */
    public function syncAll(): Response
    {
        $this->planManager->syncAll();

        return $this->redirectToRoute('sfs_subscription_admin_plans_list');
    }
}
