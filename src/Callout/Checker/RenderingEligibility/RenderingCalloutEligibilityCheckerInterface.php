<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\RenderingEligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

interface RenderingCalloutEligibilityCheckerInterface
{
    public function isEligible(CalloutInterface $callout): bool;
}
