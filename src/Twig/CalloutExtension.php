<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class CalloutExtension extends AbstractExtension
{
    public function __construct(
        private readonly int $delay,
        /** @var array<string, string> $rules */
        private readonly array $rules,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setono_sylius_callout__get_callouts', [CalloutRuntime::class, 'getCallouts']),
            new TwigFunction('setono_sylius_callout__get_callout_assignment_delay', $this->getDelay(...)),
            new TwigFunction('setono_sylius_callout__render_callout_class_attribute', [CalloutRuntime::class, 'renderCalloutClassAttribute']),
            new TwigFunction('setono_sylius_callout__render_callout', [CalloutRuntime::class, 'renderCallout'], ['is_safe' => ['html']]),
            new TwigFunction('setono_sylius_callout__callout_rule_label', $this->getCalloutRuleLabel(...)),
        ];
    }

    public function getDelay(): int
    {
        return $this->delay;
    }

    public function getCalloutRuleLabel(string $type): string
    {
        return $this->rules[$type] ?? '';
    }
}
