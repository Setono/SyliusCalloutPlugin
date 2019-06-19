<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutPlugin\Provider\ActiveCalloutProvider;
use Setono\SyliusCalloutPlugin\Provider\CalloutProviderInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;

final class ActiveCalloutProviderSpec extends ObjectBehavior
{
    function let(
        CalloutRepositoryInterface $calloutRepository,
        CalloutEligibilityCheckerInterface $calloutEligibilityChecker
    ): void {
        $this->beConstructedWith($calloutRepository, $calloutEligibilityChecker);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ActiveCalloutProvider::class);
    }

    function it_implements_callout_provider_interface(): void
    {
        $this->shouldHaveType(CalloutProviderInterface::class);
    }

    function it_provides_active_callouts(
        CalloutRepositoryInterface $calloutRepository,
        CalloutInterface $callout,
        CalloutEligibilityCheckerInterface $calloutEligibilityChecker,
        CalloutsAwareInterface $product
    ): void {
        $calloutRepository->findActive()->willReturn([$callout]);
        $calloutEligibilityChecker->isEligible($product, $callout)->willReturn(true);

        $this->getCallouts($product)->shouldReturn([$callout]);
    }
}
