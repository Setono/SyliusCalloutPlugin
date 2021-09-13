<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout\Provider;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Callout\Provider\EligibleCalloutsProvider;
use Setono\SyliusCalloutPlugin\Callout\Provider\EligibleCalloutsProviderInterface;
use Setono\SyliusCalloutPlugin\Callout\Provider\PreQualifiedCalloutsProviderInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;

final class EligibleCalloutsProviderSpec extends ObjectBehavior
{
    public function let(
        PreQualifiedCalloutsProviderInterface $preQualifiedCalloutsProvider,
        CalloutEligibilityCheckerInterface $calloutEligibilityChecker
    ): void {
        $this->beConstructedWith($preQualifiedCalloutsProvider, $calloutEligibilityChecker);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(EligibleCalloutsProvider::class);
    }

    public function it_implements_eligible_callouts_provider_interface(): void
    {
        $this->shouldHaveType(EligibleCalloutsProviderInterface::class);
    }

    public function it_provides_eligible_callouts(
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
