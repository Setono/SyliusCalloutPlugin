<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class CalloutExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_callouts', [CalloutRuntime::class, 'getCallouts']),
            new TwigFunction('render_class_attribute', [CalloutRuntime::class, 'renderClassAttribute']),
            new TwigFunction('render_callout', [CalloutRuntime::class, 'renderCallout'], ['is_safe' => ['html']]),
        ];
    }
}
