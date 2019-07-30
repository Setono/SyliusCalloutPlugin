<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;

interface CalloutEligibilityCheckerInterface
{
    public function isEligible(CalloutsAwareInterface $product, CalloutInterface $callout): bool;
}
