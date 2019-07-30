<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout\Provider;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutPlugin\Callout\Provider\EligibleCalloutsProvider;
use Setono\SyliusCalloutPlugin\Callout\Provider\EligibleCalloutsProviderInterface;
use Setono\SyliusCalloutPlugin\Callout\Provider\PreQualifiedCalloutsProviderInterface;

final class EligibleCalloutsProviderSpec extends ObjectBehavior
{
    function let(
        PreQualifiedCalloutsProviderInterface $preQualifiedCalloutsProvider,
        CalloutEligibilityCheckerInterface $calloutEligibilityChecker
    ): void {
        $this->beConstructedWith($preQualifiedCalloutsProvider, $calloutEligibilityChecker);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(EligibleCalloutsProvider::class);
    }

    function it_implements_eligible_callouts_provider_interface(): void
    {
        $this->shouldHaveType(EligibleCalloutsProviderInterface::class);
    }

    function it_provides_eligible_callouts(
        CalloutInterface $eligibleCallout,
        CalloutInterface $notEligibleCallout,
        PreQualifiedCalloutsProviderInterface $preQualifiedCalloutsProvider,
        CalloutEligibilityCheckerInterface $calloutEligibilityChecker,
        CalloutsAwareInterface $product
    ): void {
        $preQualifiedCalloutsProvider->getCallouts()->willReturn([$eligibleCallout, $notEligibleCallout]);
        $calloutEligibilityChecker->isEligible($product, $eligibleCallout)->willReturn(true);
        $calloutEligibilityChecker->isEligible($product, $notEligibleCallout)->willReturn(false);

        $this->getEligibleCallouts($product)->shouldReturn([$eligibleCallout]);
    }
}
