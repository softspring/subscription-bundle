<?php

namespace Softspring\SubscriptionBundle\Controller\Admin;

use Softspring\CoreBundle\Controller\AbstractController;
use Softspring\SubscriptionBundle\Manager\ProductManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    /**
     * @var ProductManagerInterface
     */
    protected $productManager;

    /**
     * ProductController constructor.
     *
     * @param ProductManagerInterface $productManager
     */
    public function __construct(ProductManagerInterface $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * @return Response
     *
     * TODO restrict by permission
     */
    public function syncAll(): Response
    {
        $this->productManager->syncAll();

        return $this->redirectToRoute('sfs_subscription_admin_products_list');
    }
}