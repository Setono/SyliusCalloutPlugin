<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\RenderingEligibility;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;

/**
 * Used for rendering
 */
final class CalloutChannelEligibilityChecker implements RenderingCalloutEligibilityCheckerInterface
{
    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function isEligible(CalloutInterface $callout): bool
    {
        $currentChannel = $this->channelContext->getChannel();
        if (!$currentChannel instanceof ChannelInterface) {
            return false;
        }

        return $callout->getChannels()->contains($currentChannel);
    }
}
