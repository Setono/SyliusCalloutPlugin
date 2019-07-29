<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Webmozart\Assert\Assert;

final class CompositeCalloutEligibilityChecker implements CalloutEligibilityCheckerInterface
{
    /** @var CalloutEligibilityCheckerInterface[] */
    private $calloutEligibilityCheckers;

    /**
     * @param CalloutEligibilityCheckerInterface[] $calloutEligibilityCheckers
     */
    public function __construct(array $calloutEligibilityCheckers)
    {
        Assert::notEmpty($calloutEligibilityCheckers);
        Assert::allIsInstanceOf($calloutEligibilityCheckers, CalloutEligibilityCheckerInterface::class);

        $this->calloutEligibilityCheckers = $calloutEligibilityCheckers;
    }

    public function isEligible(CalloutsAwareInterface $product, CalloutInterface $callout): bool
    {
        foreach ($this->calloutEligibilityCheckers as $calloutEligibilityChecker) {
            if (!$calloutEligibilityChecker->isEligible($product, $callout)) {
                return false;
            }
        }

        return true;
    }
}
