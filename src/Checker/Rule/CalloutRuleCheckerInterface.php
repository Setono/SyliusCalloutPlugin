<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Sylius\Component\Core\Model\ProductInterface;

interface CalloutRuleCheckerInterface
{
    public function isEligible(ProductInterface $product, array $configuration): bool;

    /**
     * This is called at runtime, so it should be as fast as possible
     */
    public function isRuntimeEligible(ProductInterface $product, array $configuration): bool;
}
