<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\CssBuilder;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Webmozart\Assert\Assert;

class SemanticUiCssClassBuilder implements CssClassBuilderInterface
{
    public function build(CalloutInterface $callout): string
    {
        $position = $callout->getPosition();
        Assert::notNull($position);

        return sprintf('ui %s attached label', str_replace('_', ' ', $position));
    }
}
