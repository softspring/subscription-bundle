<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Controller\Admin;

use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PlanController extends AbstractController
{
    public function __construct(protected PlanManagerInterface $planManager)
    {
    }

    public function syncAll(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUBSCRIPTION_ADMIN_PLANS_SYNC_ALL');

        $this->planManager->syncAll();

        return $this->redirectToRoute('sfs_subscription_admin_plans_list');
    }
}
