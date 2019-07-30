<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout;

use Exception;
use function Safe\sprintf;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

class SemanticUiCssClassBuilder implements CssClassBuilderInterface
{
    public function buildClasses(CalloutInterface $callout): string
    {
        static $semanticUiPositionClassMap = [
            CalloutInterface::POSITION_TOP_LEFT => 'top left attached label',
            CalloutInterface::POSITION_BOTTOM_LEFT => 'bottom left attached label',
            CalloutInterface::POSITION_TOP_RIGHT => 'top right attached label',
            CalloutInterface::POSITION_BOTTOM_RIGHT => 'bottom right attached label',
        ];

        $position = $callout->getPosition();
        if (!array_key_exists($position, $semanticUiPositionClassMap)) {
            throw new Exception(sprintf(
                'Unable to translate position "%s" to classes',
                $position
            ));
        }

        return $semanticUiPositionClassMap[$position];
    }
}
