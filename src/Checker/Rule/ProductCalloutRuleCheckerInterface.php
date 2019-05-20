<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Checker\Rule;

use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;

interface ProductCalloutRuleCheckerInterface
{
    public function isEligible(CalloutsAwareInterface $product, array $configuration): bool;
}
