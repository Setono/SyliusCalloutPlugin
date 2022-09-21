<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class CalloutConfigurationExtension extends AbstractExtension
{
    private bool $manualTriggeringEnabled;

    public function __construct(bool $manualTriggeringEnabled)
    {
        $this->manualTriggeringEnabled = $manualTriggeringEnabled;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setono_callout_configuration_manual_triggering', [$this, 'manualTriggering']),
        ];
    }

    public function manualTriggering(): bool
    {
        return $this->manualTriggeringEnabled;
    }
}
