<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\RenderingEligibility;

use DateTime;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

/**
 * Used for rendering
 */
final class CalloutDurationEligibilityChecker implements RenderingCalloutEligibilityCheckerInterface
{
    public function isEligible(CalloutInterface $callout): bool
    {
        $now = new DateTime();

        $startsAt = $callout->getStartsAt();
        if (null !== $startsAt && $now < $startsAt) {
            return false;
        }

        $endsAt = $callout->getEndsAt();
        if (null !== $startsAt && $now > $endsAt) {
            return false;
        }

        return true;
    }
}
