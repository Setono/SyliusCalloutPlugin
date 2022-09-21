<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\RenderingEligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Webmozart\Assert\Assert;

final class CompositeRenderingCalloutEligibilityChecker implements RenderingCalloutEligibilityCheckerInterface
{
    /** @var RenderingCalloutEligibilityCheckerInterface[] */
    private array $renderingCalloutEligibilityCheckers;

    /**
     * @param RenderingCalloutEligibilityCheckerInterface[] $renderingCalloutEligibilityCheckers
     */
    public function __construct(array $renderingCalloutEligibilityCheckers)
    {
        Assert::notEmpty($renderingCalloutEligibilityCheckers);
        Assert::allIsInstanceOf($renderingCalloutEligibilityCheckers, RenderingCalloutEligibilityCheckerInterface::class);

        $this->renderingCalloutEligibilityCheckers = $renderingCalloutEligibilityCheckers;
    }

    public function isEligible(CalloutInterface $callout): bool
    {
        foreach ($this->renderingCalloutEligibilityCheckers as $renderingCalloutEligibilityChecker) {
            if (!$renderingCalloutEligibilityChecker->isEligible($callout)) {
                return false;
            }
        }

        return true;
    }
}
