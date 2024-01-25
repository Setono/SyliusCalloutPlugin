<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\BatchIterator;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusCalloutPlugin\Event\BatchIteratorEvent;

/**
 * @template T
 *
 * @implements BatchIteratorInterface<T>
 */
final class BatchIterator implements BatchIteratorInterface
{
    use ORMManagerTrait;

    /** @var list<callable> */
    private array $modifications = [];

    /**
     * @param class-string<T> $class
     */
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        ManagerRegistry $managerRegistry,
        private readonly string $class,
    ) {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param callable(QueryBuilder): void $callable
     */
    public function modify(callable $callable): static
    {
        $this->modifications[] = $callable;

        return $this;
    }

    /**
     * @return SimpleBatchIteratorAggregate<array-key, T>
     */
    public function getIterator(): SimpleBatchIteratorAggregate
    {
        $manager = $this->getManager($this->class);
        $qb = $manager
            ->createQueryBuilder()
            ->select('o')
            ->from($this->class, 'o');

        foreach ($this->modifications as $modification) {
            $modification($qb);
        }

        $this->eventDispatcher->dispatch(new BatchIteratorEvent($qb, $this->class));

        /** @var SimpleBatchIteratorAggregate<array-key, T> $iterator */
        $iterator = SimpleBatchIteratorAggregate::fromQuery($qb->getQuery(), 100);

        return $iterator;
    }
}
