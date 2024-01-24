<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\RenderingEligibility;

use DateTime;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

final class CalloutDurationRenderingEligibilityChecker implements CalloutRenderingEligibilityCheckerInterface
{
    public function isEligible(CalloutInterface $callout): bool
    {
        $now = new DateTime();

        $startsAt = $callout->getStartsAt();
        if (null !== $startsAt && $now < $startsAt) {
            return false;
        }

        $endsAt = $callout->getEndsAt();

        return null === $endsAt || $now < $endsAt;
    }
}
