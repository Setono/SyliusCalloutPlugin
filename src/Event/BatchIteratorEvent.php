<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Event;

use Doctrine\ORM\QueryBuilder;

final class BatchIteratorEvent
{
    /**
     * @param class-string $class
     */
    public function __construct(public readonly QueryBuilder $queryBuilder, public readonly string $class)
    {
    }
}
