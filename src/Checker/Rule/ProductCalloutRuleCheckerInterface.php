<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;

interface ProductCalloutRuleCheckerInterface
{
    public function isEligible(CalloutsAwareInterface $product, array $configuration): bool;
}
