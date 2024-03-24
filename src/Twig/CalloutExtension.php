<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class CalloutExtension extends AbstractExtension
{
    public function __construct(private readonly int $delay)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_callouts', [CalloutRuntime::class, 'getCallouts']),
            new TwigFunction('get_callout_assignment_delay', $this->getDelay(...)),
            new TwigFunction('render_callout_class_attribute', [CalloutRuntime::class, 'renderCalloutClassAttribute']),
            new TwigFunction('render_callout', [CalloutRuntime::class, 'renderCallout'], ['is_safe' => ['html']]),
            new TwigFunction('render_callout_style', [CalloutRuntime::class, 'renderCalloutStyle']),
        ];
    }

    public function getDelay(): int
    {
        return $this->delay;
    }
}
