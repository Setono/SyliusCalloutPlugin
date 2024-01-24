<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Renderer;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Stringable;

interface CalloutRendererInterface
{
    public function render(CalloutInterface $callout): Stringable|string;
}
