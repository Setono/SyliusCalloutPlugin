<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\RenderingEligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

interface CalloutRenderingEligibilityCheckerInterface
{
    /**
     * Returns true if the callout is eligible for rendering
     */
    public function isEligible(CalloutInterface $callout): bool;
}
