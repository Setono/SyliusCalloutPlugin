<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Assigner;

use Doctrine\ORM\EntityRepository;
use Setono\DoctrineORMBatcher\Factory\BestIdRangeBatcherFactory;
use Setono\SyliusCalloutPlugin\Message\Command\AssignProductCallouts;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CalloutAssigner implements CalloutAssignerInterface
{
    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(ProductRepositoryInterface $productRepository, MessageBusInterface $messageBus)
    {
        $this->productRepository = $productRepository;
        $this->messageBus = $messageBus;
    }

    public function assign(): void
    {
        if (!$this->productRepository instanceof EntityRepository) {
            return;
        }

        $qb = $this->productRepository->createQueryBuilder('o');
        $qb->andWhere('o.enabled = 1');

        $batcherFactory = new BestIdRangeBatcherFactory();
        $batcher = $batcherFactory->create($qb);

        foreach ($batcher->getBatches() as $batch) {
            $this->messageBus->dispatch(new AssignProductCallouts($batch));
        }
    }
}
