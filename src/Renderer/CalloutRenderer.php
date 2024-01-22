<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Renderer;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

final class CalloutRenderer implements CalloutRendererInterface
{
    public function render(CalloutInterface $callout): string
    {
        return (string) $callout->getText();
    }
}
