<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Message\Command;

class AssignProductCallouts
{
    /** @var array */
    private $productIds;

    public function __construct(array $productIds)
    {
        $this->productIds = $productIds;
    }

    public function getProductIds(): array
    {
        return $this->productIds;
    }
}
