<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout;

use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Webmozart\Assert\Assert;

class SemanticUiCssClassBuilder implements CssClassBuilderInterface
{
    public function buildClasses(CalloutInterface $callout): string
    {
        $position = $callout->getPosition();
        Assert::notNull($position);
        Assert::oneOf($position, Callout::getAllowedPositions());

        return self::getCssClasses($position);
    }

    private static function getCssClasses(string $position): string
    {
        //add custom vertically class to display at right or left
        if ($position === 'left' || $position === 'right'){
            $class = "vertically ".$position;
        }else{
            $class = str_replace('_', ' ', $position);
        }

        $class .= ' attached label';

        return $class;
    }
}
