<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Eligibility;

use Setono\CompositeCompilerPass\CompositeService;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Core\Model\ProductInterface;

/**
 * @extends CompositeService<CalloutEligibilityCheckerInterface>
 */
final class CompositeCalloutEligibilityChecker extends CompositeService implements CalloutEligibilityCheckerInterface
{
    public function isEligible(ProductInterface $product, CalloutInterface $callout): bool
    {
        foreach ($this->services as $service) {
            if (!$service->isEligible($product, $callout)) {
                return false;
            }
        }

        return true;
    }
}
