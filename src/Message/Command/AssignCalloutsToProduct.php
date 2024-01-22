<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Sylius\Component\Product\Model\ProductInterface;

/**
 * Dispatch this message to trigger the assigning of pre-qualified callouts to a single product
 */
final class AssignCalloutsToProduct implements CommandInterface
{
    /**
     * This is the product code
     */
    public string $product;

    public function __construct(string|ProductInterface $product)
    {
        if ($product instanceof ProductInterface) {
            $product = (string) $product->getCode();
        }

        $this->product = $product;
    }
}
