<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\RenderingEligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

/**
 * Used for rendering
 */
final class CalloutToggleEligibilityChecker implements RenderingCalloutEligibilityCheckerInterface
{
    public function isEligible(CalloutInterface $callout): bool
    {
        return $callout->isEnabled();
    }
}
