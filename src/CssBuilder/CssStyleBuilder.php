<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\CssBuilder;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

class CssStyleBuilder implements CssStyleBuilderInterface
{
    public function build(CalloutInterface $callout): string
    {
        $style = 'z-index: 999;';
        if ($callout->getColor()) {
            $style .= sprintf(' color: %s;', $callout->getColor());
        }
        if ($callout->getBackgroundColor()) {
            $style .= sprintf(' background-color: %s;', $callout->getBackgroundColor());
        }

        return $style;
    }
}
