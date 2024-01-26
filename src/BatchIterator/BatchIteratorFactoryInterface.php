<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\BatchIterator;

/**
 * @internal The batch iterator functionality is internal because we want to extract it to an external library and bundle
 */
interface BatchIteratorFactoryInterface
{
    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return BatchIteratorInterface<T>
     */
    public function create(string $class): BatchIteratorInterface;
}
