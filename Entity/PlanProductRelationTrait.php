<?php

namespace Softspring\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\SubscriptionBundle\Model\ProductInterface;

trait PlanProductRelationTrait
{
    /**
     * @var ProductInterface|null
     * @ORM\ManyToOne(targetEntity="Softspring\SubscriptionBundle\Model\ProductInterface", inversedBy="plans")
     */
    protected $product;

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }
}