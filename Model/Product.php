<?php

namespace Softspring\SubscriptionBundle\Model;

abstract class Product implements ProductInterface
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var int|null
     */
    protected $type;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int|null $type
     */
    public function setType(?int $type): void
    {
        $this->type = $type;
    }
}