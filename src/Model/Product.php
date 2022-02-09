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
     *
     * @todo review if needed
     */
    protected $type;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): void
    {
        $this->type = $type;
    }
}
