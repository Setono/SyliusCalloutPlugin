<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig\Extension;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Exception;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class CalloutExtension extends AbstractExtension
{
    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('setono_callouts', [$this, 'filterCallouts']),
        ];
    }

    /**
     * @param Collection|CalloutInterface[] $callouts
     *
     * @return Collection|CalloutInterface[]
     *
     * @throws Exception
     */
    public function filterCallouts(Collection $callouts): Collection
    {
        if ($callouts->isEmpty()) {
            return $callouts;
        }

        $now = new DateTime();

        return $callouts->filter(function (CalloutInterface $callout) use ($now) {
            if (!$callout->isEnabled()) {
                return false;
            }

            $start = $callout->getTimePeriodStart();
            if ($start !== null && $now < $start) {
                return false;
            }

            $end = $callout->getTimePeriodEnd();
            if ($end !== null && $now > $end) {
                return false;
            }

            $channelFound = false;
            foreach ($callout->getChannels() as $channel) {
                if ($channel->getCode() === $this->channelContext->getChannel()->getCode()) {
                    $channelFound = true;

                    break;
                }
            }

            if (!$channelFound) {
                return false;
            }

            return true;
        });
    }
}
