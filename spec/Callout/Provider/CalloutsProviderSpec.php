<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout\Provider;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\Provider\CalloutsProvider;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Callout\Provider\PreQualifiedCalloutsProviderInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;

final class CalloutsProviderSpec extends ObjectBehavior
{
    function let(CalloutRepositoryInterface $calloutRepository): void
    {
        $this->beConstructedWith($calloutRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CalloutsProvider::class);
    }

    function it_implements_pre_qualified_callouts_provider_interface(): void
    {
        $this->shouldHaveType(PreQualifiedCalloutsProviderInterface::class);
    }

    function it_provides_active_callouts(
        CalloutRepositoryInterface $calloutRepository,
        CalloutInterface $callout
    ): void {
        $calloutRepository->findOrdered()->willReturn([$callout]);
        $this->getCallouts()->shouldReturn([$callout]);
    }
}
