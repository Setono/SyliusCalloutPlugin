<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Eligibility;

use Setono\SyliusCalloutPlugin\Checker\Rule\CalloutRuleCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class CalloutRulesEligibilityChecker implements CalloutEligibilityCheckerInterface
{
    private ServiceRegistryInterface $ruleRegistry;

    public function __construct(ServiceRegistryInterface $ruleRegistry)
    {
        $this->ruleRegistry = $ruleRegistry;
    }

    public function isEligible(ProductInterface $product, CalloutInterface $callout): bool
    {
        if (!$callout->hasRules()) {
            return true;
        }

        // All rules should pass for Product to be eligible
        foreach ($callout->getRules() as $rule) {
            if (!$this->isEligibleToRule($product, $rule)) {
                return false;
            }
        }

        return true;
    }

    private function isEligibleToRule(ProductInterface $product, CalloutRuleInterface $rule): bool
    {
        /** @var CalloutRuleCheckerInterface $checker */
        $checker = $this->ruleRegistry->get((string) $rule->getType());

        return $checker->isEligible($product, $rule->getConfiguration());
    }
}
