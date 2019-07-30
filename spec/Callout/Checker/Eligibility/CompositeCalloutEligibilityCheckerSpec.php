<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility\CompositeCalloutEligibilityChecker;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;

final class CompositeCalloutEligibilityCheckerSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CompositeCalloutEligibilityChecker::class);
    }

    function it_implements_callout_eligibility_checker_interface(): void
    {
        $this->shouldHaveType(CalloutEligibilityCheckerInterface::class);
    }

    function it_checks_eligibility(
        ProductInterface $product,
        CalloutInterface $callout,
        CalloutEligibilityCheckerInterface $eligibilityChecker
    ): void {
        $this->beConstructedWith([$eligibilityChecker]);
        $eligibilityChecker->isEligible($product, $callout)->willReturn(true);

        $this->isEligible($product, $callout)->shouldReturn(true);
    }

    function it_not_eligible_when_at_least_one_checker_not_eligible(
        ProductInterface $product,
        CalloutInterface $callout,
        CalloutEligibilityCheckerInterface $eligibilityChecker1,
        CalloutEligibilityCheckerInterface $eligibilityChecker2
    ): void {
        $this->beConstructedWith([$eligibilityChecker1, $eligibilityChecker2]);
        $eligibilityChecker1->isEligible($product, $callout)->willReturn(true);
        $eligibilityChecker2->isEligible($product, $callout)->willReturn(false);

        $this->isEligible($product, $callout)->shouldReturn(false);
    }
}
