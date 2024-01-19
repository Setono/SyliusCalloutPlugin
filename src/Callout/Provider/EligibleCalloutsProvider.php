<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Provider;

use Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;

final class EligibleCalloutsProvider implements EligibleCalloutsProviderInterface
{
    private PreQualifiedCalloutsProviderInterface $preQualifiedCalloutsProvider;

    private CalloutEligibilityCheckerInterface $calloutEligibilityChecker;

    public function __construct(
        PreQualifiedCalloutsProviderInterface $preQualifiedCalloutsProvider,
        CalloutEligibilityCheckerInterface $calloutEligibilityChecker,
    ) {
        $this->preQualifiedCalloutsProvider = $preQualifiedCalloutsProvider;
        $this->calloutEligibilityChecker = $calloutEligibilityChecker;
    }

    public function getEligibleCallouts(CalloutsAwareInterface $product): array
    {
        return array_filter(
            $this->preQualifiedCalloutsProvider->getCallouts(),
            function (CalloutInterface $callout) use ($product): bool {
                return $this->calloutEligibilityChecker->isEligible($product, $callout);
            },
        );
    }
}
