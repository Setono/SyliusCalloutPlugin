<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\RenderingEligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class CalloutChannelRenderingEligibilityChecker implements CalloutRenderingEligibilityCheckerInterface
{
    private ChannelContextInterface $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function isEligible(CalloutInterface $callout): bool
    {
        return $callout->getChannels()->contains($this->channelContext->getChannel());
    }
}
