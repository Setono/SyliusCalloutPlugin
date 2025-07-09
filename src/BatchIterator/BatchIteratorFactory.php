<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\BatchIterator;

use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\Doctrine\ORMTrait;

/**
 * @internal The batch iterator functionality is internal because we want to extract it to an external library and bundle
 */
final class BatchIteratorFactory implements BatchIteratorFactoryInterface
{
    use ORMTrait;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        ManagerRegistry $managerRegistry,
    ) {
        $this->managerRegistry = $managerRegistry;
    }

    public function create(string $class): BatchIteratorInterface
    {
        return new BatchIterator($this->eventDispatcher, $this->managerRegistry, $class);
    }
}
