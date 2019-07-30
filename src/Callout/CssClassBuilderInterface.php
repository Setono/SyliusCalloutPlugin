<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

interface CssClassBuilderInterface
{
    public function buildClasses(CalloutInterface $callout): string;
}
