<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutsPlugin\Provider\ActiveCalloutProvider;
use Setono\SyliusCalloutsPlugin\Provider\CalloutProviderInterface;
use Setono\SyliusCalloutsPlugin\Repository\CalloutRepositoryInterface;

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
