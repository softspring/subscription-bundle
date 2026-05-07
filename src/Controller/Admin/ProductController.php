<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Controller\Admin;

use Softspring\SubscriptionBundle\Manager\ProductManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    public function __construct(protected ProductManagerInterface $productManager)
    {
    }

    public function syncAll(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUBSCRIPTION_ADMIN_PRODUCTS_SYNC_ALL');

        $this->productManager->syncAll();

        return $this->redirectToRoute('sfs_subscription_admin_products_list');
    }
}
