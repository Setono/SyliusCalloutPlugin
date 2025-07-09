<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Event;

use Doctrine\ORM\QueryBuilder;

/**
 * @internal The batch iterator functionality is internal because we want to extract it to an external library and bundle
 */
final readonly class BatchIteratorEvent
{
    /**
     * @param class-string $class
     */
    public function __construct(public QueryBuilder $queryBuilder, public string $class)
    {
    }
}
