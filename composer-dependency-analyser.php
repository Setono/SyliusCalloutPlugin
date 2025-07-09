<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    ->addPathToExclude(__DIR__ . '/tests')
    ->ignoreErrorsOnPackage('sylius/grid-bundle', [ErrorType::UNUSED_DEPENDENCY]) // We use this for defining our grid in the admin
    ->ignoreErrorsOnPackage('symfony/twig-bundle', [ErrorType::UNUSED_DEPENDENCY]) // This creates the 'twig' service which we rely on
;
