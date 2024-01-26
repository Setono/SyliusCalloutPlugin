<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Sylius\Component\Core\Model\ProductInterface;

abstract class AbstractCalloutRuleChecker implements CalloutRuleCheckerInterface
{
    public function isRuntimeEligible(ProductInterface $product, array $configuration): bool
    {
        return true;
    }
}
