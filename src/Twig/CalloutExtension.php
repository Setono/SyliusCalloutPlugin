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
            new TwigFunction('render_class_attribute', [CalloutRuntime::class, 'renderClassAttribute']),
            new TwigFunction('render_callout', [CalloutRuntime::class, 'renderCallout'], ['is_safe' => ['html']]),
        ];
    }

    public function getDelay(): int
    {
        return $this->delay;
    }
}
