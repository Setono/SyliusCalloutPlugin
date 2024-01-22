<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\RenderingEligibility;

use Setono\CompositeCompilerPass\CompositeService;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

/**
 * @extends CompositeService<CalloutRenderingEligibilityCheckerInterface>
 */
final class CompositeCalloutRenderingEligibilityChecker extends CompositeService implements CalloutRenderingEligibilityCheckerInterface
{
    /** @var array<string, bool> */
    private array $eligibilityCheckCache = [];

    public function isEligible(CalloutInterface $callout): bool
    {
        // todo move the cache part to a decorator?
        $cacheKey = (string) $callout->getCode();

        if (!isset($this->eligibilityCheckCache[$cacheKey])) {
            $eligibilityCheckResult = true;

            foreach ($this->services as $service) {
                if (!$service->isEligible($callout)) {
                    $eligibilityCheckResult = false;

                    break;
                }
            }

            $this->eligibilityCheckCache[$cacheKey] = $eligibilityCheckResult;
        }

        return $this->eligibilityCheckCache[$cacheKey];
    }
}
