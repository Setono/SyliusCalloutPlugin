<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\BatchIterator;

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
