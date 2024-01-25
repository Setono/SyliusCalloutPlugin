<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Provider;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class RenderingCalloutProvider implements RenderingCalloutProviderInterface
{
    /** @var array<string, array<string, array<string, CalloutInterface|null>>> */
    private array $callouts = [];

    public function __construct(
        private readonly CalloutRepositoryInterface $calloutRepository,
        private readonly ChannelContextInterface $channelContext,
        private readonly LocaleContextInterface $localeContext,
    ) {
    }

    public function getByCodes(array $codes, ChannelInterface $channel = null, string $localeCode = null): array
    {
        $channel = $channel ?? $this->channelContext->getChannel();
        $channelCode = (string) $channel->getCode();

        $localeCode = $localeCode ?? $this->localeContext->getLocaleCode();

        if (!isset($this->callouts[$channelCode])) {
            $this->callouts[$channelCode] = [];
        }

        if (!isset($this->callouts[$channelCode][$localeCode])) {
            $this->callouts[$channelCode][$localeCode] = [];
        }

        $missingCodes = [];
        foreach ($codes as $code) {
            if (!array_key_exists($code, $this->callouts[$channelCode][$localeCode])) {
                $missingCodes[] = $code;
                $this->callouts[$channelCode][$localeCode][$code] = null;
            }
        }

        if ([] !== $missingCodes) {
            $missingCallouts = $this->calloutRepository->findByCodes($missingCodes, $channel, $localeCode);
            foreach ($missingCallouts as $callout) {
                $this->callouts[$channelCode][$localeCode][(string) $callout->getCode()] = $callout;
            }
        }

        $callouts = [];

        foreach ($codes as $code) {
            $callouts[] = $this->callouts[$channelCode][$localeCode][$code];
        }

        return array_values(array_filter($callouts));
    }
}
