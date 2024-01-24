<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\CssClassBuilder;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Webmozart\Assert\Assert;

class SemanticUiCssClassBuilder implements CssClassBuilderInterface
{
    public function build(CalloutInterface $callout): string
    {
        $position = $callout->getPosition();
        Assert::notNull($position);

        return self::getCssClasses($position);
    }

    private static function getCssClasses(string $position): string
    {
        $class = 'attached label ';
        $class .= str_replace('_', ' ', $position);

        return $class;
    }
}
