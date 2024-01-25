<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Provider;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Channel\Model\ChannelInterface;

/**
 * The rendering callout provider will provide callouts in a context of rendering the callouts.
 * This has the implication that we want both the channel and locale to be able to return the correct callouts
 */
interface RenderingCalloutProviderInterface
{
    /**
     * Will return a list of callouts from the given callout codes
     *
     * @param list<string> $codes
     * @param ChannelInterface|null $channel if null, the channel context will be used
     * @param string|null $localeCode if null, the locale context will be used
     *
     * @return list<CalloutInterface>
     */
    public function getByCodes(array $codes, ChannelInterface $channel = null, string $localeCode = null): array;
}
