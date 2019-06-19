<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig\Extension;

use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Twig\TwigFunction;
use Webmozart\Assert\Assert;
use Twig\Extension\AbstractExtension;

final class RenderCalloutExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('setono_render_callouts', [$this, 'renderCallouts'], ['is_safe' => ['html']]),
        ];
    }

    public function renderCallouts(CalloutsAwareInterface $product, string $position): string
    {
        Assert::oneOf($position, Callout::getAllowedPositions());
        $callouts = $product->getCallouts();
        $result = '';

        foreach ($callouts as $callout) {
            if ($callout->getPosition() === $position) {
                $result .= $callout->getHtml();
            }
        }

        return $result;
    }
}
