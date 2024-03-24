<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\CssBuilder;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

interface CssClassBuilderInterface
{
    public function build(CalloutInterface $callout): string;
}
