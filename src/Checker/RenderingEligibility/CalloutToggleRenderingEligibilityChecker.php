<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\RenderingEligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

final class CalloutToggleRenderingEligibilityChecker implements CalloutRenderingEligibilityCheckerInterface
{
    public function isEligible(CalloutInterface $callout): bool
    {
        return $callout->isEnabled();
    }
}
