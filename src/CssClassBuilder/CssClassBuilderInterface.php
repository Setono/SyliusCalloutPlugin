<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\CssClassBuilder;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

interface CssClassBuilderInterface
{
    public function build(CalloutInterface $callout): string;
}
