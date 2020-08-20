<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use InvalidArgumentException;
use Sylius\Component\Product\Model\ProductInterface;

final class AssignEligibleCalloutsToProduct implements CommandInterface
{
    /** @var int|string */
    private $productId;

    /**
     * @param mixed|ProductInterface $product
     */
    public function __construct($product)
    {
        if ($product instanceof ProductInterface) {
            $product = $product->getId();
        }

        if (!is_int($product) && !is_string($product)) {
            throw new InvalidArgumentException('The $product must be either int, string, or ProductInterface');
        }

        $this->productId = $product;
    }

    /**
     * @return int|string
     */
    public function getProductId()
    {
        return $this->productId;
    }
}
