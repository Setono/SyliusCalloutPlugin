<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Event;

use Doctrine\ORM\QueryBuilder;

final class ProductQueryBuilderEvent
{
    public function __construct(public readonly QueryBuilder $queryBuilder)
    {
    }
}
