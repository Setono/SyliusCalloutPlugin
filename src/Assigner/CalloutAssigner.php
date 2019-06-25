<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Assigner;

use Setono\DoctrineORMBatcher\Batcher\IdBatcherInterface;
use Setono\SyliusCalloutPlugin\Message\Command\AssignProductCallouts;
use Symfony\Component\Messenger\MessageBusInterface;

final class CalloutAssigner implements CalloutAssignerInterface
{
    /** @var IdBatcherInterface */
    private $idBatcher;

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(IdBatcherInterface $idBatcher, MessageBusInterface $messageBus)
    {
        $this->idBatcher = $idBatcher;
        $this->messageBus = $messageBus;
    }

    public function assign(): void
    {
        foreach ($this->idBatcher->getBatches() as $batch) {
            $this->messageBus->dispatch(new AssignProductCallouts($batch));
        }
    }
}
