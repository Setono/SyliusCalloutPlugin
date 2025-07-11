<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\CssClassBuilder;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Webmozart\Assert\Assert;

final class SemanticUiCssClassBuilder implements CssClassBuilderInterface
{
    public function build(CalloutInterface $callout): string
    {
        return 'badge text-bg-success';
    }
}
