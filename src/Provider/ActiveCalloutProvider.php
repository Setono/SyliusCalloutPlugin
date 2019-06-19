<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Provider;

use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;

final class ActiveCalloutProvider implements CalloutProviderInterface
{
    /** @var CalloutRepositoryInterface */
    private $calloutRepository;

    /** @var CalloutEligibilityCheckerInterface */
    private $calloutEligibilityChecker;

    public function __construct(
        CalloutRepositoryInterface $calloutRepository,
        CalloutEligibilityCheckerInterface $calloutEligibilityChecker
    ) {
        $this->calloutRepository = $calloutRepository;
        $this->calloutEligibilityChecker = $calloutEligibilityChecker;
    }

    public function getCallouts(CalloutsAwareInterface $product): array
    {
        $callouts = [];

        foreach ($this->calloutRepository->findActive() as $callout) {
            if ($this->calloutEligibilityChecker->isEligible($product, $callout)) {
                $callouts[] = $callout;
            }
        }

        return $callouts;
    }
}
