<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig\Extension;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Exception;
use function Safe\preg_replace;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

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

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setono_callout_classes', [$this, 'calloutClasses']),
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

            $start = $callout->getStartsAt();
            if ($start !== null && $now < $start) {
                return false;
            }

            $end = $callout->getEndsAt();
            if ($end !== null && $now > $end) {
                return false;
            }

            $currentChannel = $this->channelContext->getChannel();
            if (!$callout->getChannels()->contains($currentChannel)) {
                return false;
            }

            return true;
        });
    }

    public function calloutClasses(CalloutInterface $callout): string
    {
        $classes = [
            'red', // @todo Add color field to Callout
            $this->getSemanticUiClassesFromPosition($callout->getPosition()),
            'setono-callout',
            'setono-callout-code-' . $this->sanitizeClass((string) $callout->getCode()),
        ];

        if ($callout->getPosition() !== null) {
            $classes[] = 'setono-callout-position-' . $this->sanitizeClass($callout->getPosition());
        }

        return implode(' ', $classes);
    }

    private function getSemanticUiClassesFromPosition(string $position): string
    {
        static $semanticUiPositionClassMap = [
            CalloutInterface::POSITION_TOP_LEFT => 'top left attached label',
            CalloutInterface::POSITION_BOTTOM_LEFT => 'bottom left attached label',
            CalloutInterface::POSITION_TOP_RIGHT => 'top right attached label',
            CalloutInterface::POSITION_BOTTOM_RIGHT => 'bottom right attached label',
        ];

        return $semanticUiPositionClassMap[$position];
    }

    private function sanitizeClass(string $str): string
    {
        return strtolower(preg_replace('/[^0-9A-Za-z\-]+/', '-', $str));
    }
}
