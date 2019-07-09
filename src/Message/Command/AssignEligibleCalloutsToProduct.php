<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Sylius\Component\Product\Model\ProductInterface;

final class AssignEligibleCalloutsToProduct implements CommandInterface
{
    /** @var mixed|ProductInterface */
    private $productId;

    /**
     * @param mixed|ProductInterface $product
     */
    public function __construct($product)
    {
        $this->productId = $product instanceof ProductInterface ? $product->getId() : $product;
    }

    public function getProductId()
    {
        return $this->productId;
    }
}
