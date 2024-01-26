<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Eligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface CalloutEligibilityCheckerInterface
{
    public function isEligible(ProductInterface $product, CalloutInterface $callout): bool;

    /**
     * This is called at runtime, so it should be as fast as possible
     */
    public function isRuntimeEligible(ProductInterface $product, CalloutInterface $callout): bool;
}
