<?php

namespace Softspring\SubscriptionBundle\Tests\Model\Examples;

use Doctrine\Common\Collections\ArrayCollection;
use Softspring\SubscriptionBundle\Model\Product;
use Softspring\SubscriptionBundle\Model\ProductPlansRelationTrait;

class ProductBaseExample extends Product
{
    use ProductPlansRelationTrait;

    public function __construct()
    {
        $this->plans = new ArrayCollection();
    }
}