<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerTrait;
use Softspring\SubscriptionBundle\Model\ProductInterface;

class ProductManager implements ProductManagerInterface
{
    use CrudlEntityManagerTrait;

    public function __construct(protected EntityManagerInterface $em)
    {
    }

    public function getTargetClass(): string
    {
        return ProductInterface::class;
    }

    public function syncAll(): void
    {
        //        $dbProducts = new ArrayCollection($this->getRepository()->findAll());
        //        $platformProducts = $this->api->get('product')->list();
        //
        //        /** @var ProductResponse $platformProduct */
        //        foreach ($platformProducts as $platformProduct) {
        //            /** @var ProductInterface $dbProduct */
        //            $dbProduct = $dbProducts->filter(function(ProductInterface $product) use ($platformProduct) {
        //                return $product->getPlatformId() == $platformProduct->getId();
        //            })->first();
        //
        //            if (!$dbProduct) {
        //                // not found, create in db
        //                $dbProduct = $this->createEntity();
        //                $dbProduct->setPlatform($this->api->platformId());
        //                $dbProduct->setPlatformId($platformProduct->getId());
        //            }
        //
        //            $dbProduct->setName($platformProduct->getName());
        // //            $dbProduct->setActive($platformProduct->getActive());
        // //            $dbProduct->setAmount($platformProduct->getAmount());
        // //            $dbProduct->setCurrency($platformProduct->getCurrency());
        //            $dbProduct->setTestMode($platformProduct->isTesting());
        // //            $dbProduct->setInterval($platformProduct->getInterval());
        //            $dbProduct->setPlatformData($platformProduct->getPlatformNativeArray());
        //            $dbProduct->setPlatformLastSync(new \DateTime('now'));
        //            $dbProduct->setPlatformConflict(false);
        //            $this->saveEntity($dbProduct);
        //
        //            $dbProducts->removeElement($dbProduct);
        //        }
        //
        //        /** @var ProductInterface $dbProduct */
        //        foreach ($dbProducts as $dbProduct) {
        //            $dbProduct->setPlatformConflict(true);
        //            $this->saveEntity($dbProduct);
        //        }
    }
}
