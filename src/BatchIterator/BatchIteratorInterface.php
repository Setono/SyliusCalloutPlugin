<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\BatchIterator;

use Doctrine\ORM\QueryBuilder;

/**
 * @template T
 *
 * @extends \IteratorAggregate<array-key, T>
 */
interface BatchIteratorInterface extends \IteratorAggregate
{
    /**
     * @param callable(QueryBuilder): void $callable
     */
    public function modify(callable $callable): static;
}
