<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Checker\Eligibility;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutRulesEligibilityChecker;
use Setono\SyliusCalloutPlugin\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class CalloutRulesEligibilityCheckerSpec extends ObjectBehavior
{
    function let(ServiceRegistryInterface $ruleRegistry): void
    {
        $this->beConstructedWith($ruleRegistry);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CalloutRulesEligibilityChecker::class);
    }

    function it_implements_callout_eligibility_checker_interface(): void
    {
        $this->shouldHaveType(CalloutEligibilityCheckerInterface::class);
    }

    function it_checks_eligibility_for_callout_without_rules(
        CalloutInterface $callout,
        CalloutsAwareInterface $product
    ): void {
        $callout->hasRules()->willReturn(false);

        $this->isEligible($product, $callout)->shouldReturn(false);
    }

    function it_checks_eligibility(
        CalloutInterface $callout,
        CalloutRuleInterface $rule,
        ServiceRegistryInterface $ruleRegistry,
        ProductCalloutRuleCheckerInterface $hasTaxonRuleChecker,
        CalloutsAwareInterface $product
    ): void {
        $callout->hasRules()->willReturn(true);
        $callout->getRules()->willReturn(new ArrayCollection([$rule->getWrappedObject()]));
        $rule->getType()->willReturn('has_taxon');
        $rule->getConfiguration()->willReturn(['taxons' => ['mugs', 't_shirts']]);
        $ruleRegistry->get('has_taxon')->willReturn($hasTaxonRuleChecker);
        $hasTaxonRuleChecker->isEligible($product, ['taxons' => ['mugs', 't_shirts']])->willReturn(true);

        $this->isEligible($product, $callout)->shouldReturn(true);
    }
}
