<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\RenderingEligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final readonly class CalloutChannelRenderingEligibilityChecker implements CalloutRenderingEligibilityCheckerInterface
{
    public function __construct(private ChannelContextInterface $channelContext)
    {
    }

    public function isEligible(CalloutInterface $callout): bool
    {
        return $callout->getChannels()->contains($this->channelContext->getChannel());
    }
}
