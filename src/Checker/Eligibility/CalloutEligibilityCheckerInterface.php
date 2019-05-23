<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Checker\Eligibility;

use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;

interface CalloutEligibilityCheckerInterface
{
    public function isEligible(CalloutsAwareInterface $product, CalloutInterface $callout): bool;
}
