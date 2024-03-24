<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\CssBuilder;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

interface CssStyleBuilderInterface
{
    public function build(CalloutInterface $callout): string;
}
