<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Sylius\Component\Core\Model\ProductInterface;

interface CalloutRuleCheckerInterface
{
    public function isEligible(ProductInterface $product, array $configuration): bool;
}
