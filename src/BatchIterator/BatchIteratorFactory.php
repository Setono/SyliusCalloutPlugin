<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\BatchIterator;

use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;

final class BatchIteratorFactory implements BatchIteratorFactoryInterface
{
    use ORMManagerTrait;

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
