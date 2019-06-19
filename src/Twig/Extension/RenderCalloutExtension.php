<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig\Extension;

use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Webmozart\Assert\Assert;

final class RenderCalloutExtension extends \Twig_Extension
{
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('setono_render_callouts', [$this, 'renderCallouts'], ['is_safe' => ['html']]),
        ];
    }

    public function renderCallouts(CalloutsAwareInterface $product, string $position): string
    {
        Assert::oneOf($position, Callout::getAllowedPositions());
        $callouts = $product->getCallouts();
        $result = '';

        foreach ($callouts as $callout) {
            if ($position === $callout->getPosition()) {
                $result .= $callout->getHtml();
            }
        }

        return $result;
    }
}
