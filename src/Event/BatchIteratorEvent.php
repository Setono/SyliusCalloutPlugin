<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Event;

use Doctrine\ORM\QueryBuilder;

/**
 * @internal The batch iterator functionality is internal because we want to extract it to an external library and bundle
 */
final class BatchIteratorEvent
{
    /**
     * @param class-string $class
     */
    public function __construct(public readonly QueryBuilder $queryBuilder, public readonly string $class)
    {
    }
}
