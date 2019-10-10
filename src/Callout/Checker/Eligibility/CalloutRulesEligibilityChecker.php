<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility;

use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * Used for persisting
 */
final class CalloutRulesEligibilityChecker implements CalloutEligibilityCheckerInterface
{
    /** @var ServiceRegistryInterface */
    private $ruleRegistry;

    /** @var bool */
    private $isNoRulesEligible;

    public function __construct(ServiceRegistryInterface $ruleRegistry, bool $isNoRulesEligible = false)
    {
        $this->ruleRegistry = $ruleRegistry;
        $this->isNoRulesEligible = $isNoRulesEligible;
    }

    public function isEligible(CalloutsAwareInterface $product, CalloutInterface $callout): bool
    {
        if (!$callout->hasRules()) {
            return $this->isNoRulesEligible;
        }

        // All rules should pass for Product to be eligible
        foreach ($callout->getRules() as $rule) {
            if (!$this->isEligibleToRule($product, $rule)) {
                return false;
            }
        }

        return true;
    }

    private function isEligibleToRule(CalloutsAwareInterface $product, CalloutRuleInterface $rule): bool
    {
        /** @var ProductCalloutRuleCheckerInterface $checker */
        $checker = $this->ruleRegistry->get($rule->getType());

        return $checker->isEligible($product, $rule->getConfiguration());
    }
}
