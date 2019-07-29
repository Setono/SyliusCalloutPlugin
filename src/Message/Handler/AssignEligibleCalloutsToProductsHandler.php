<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityManager;
use Setono\DoctrineORMBatcher\Factory\BestIdRangeBatcherFactory;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProducts;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProductsBatch;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class AssignEligibleCalloutsToProductsHandler implements MessageHandlerInterface
{
    /** @var CalloutRepositoryInterface */
    private $calloutRepository;

    /** @var EntityManager */
    private $calloutManager;

    /** @var EntityRepository|ProductRepositoryInterface */
    private $productRepository;

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(
        CalloutRepositoryInterface $calloutRepository,
        EntityManager $calloutManager,
        ProductRepositoryInterface $productRepository,
        MessageBusInterface $messageBus
    ) {
        $this->calloutRepository = $calloutRepository;
        $this->calloutManager = $calloutManager;
        $this->productRepository = $productRepository;
        $this->messageBus = $messageBus;
    }

    public function __invoke(AssignEligibleCalloutsToProducts $message): void
    {
        // We don't want assign process to actually start if another change
        // was done on any callout as this lead to another assign triggering
        // in few seconds which means double work
        $triggeredAt = $message->getTriggeredAt();
        if ($this->calloutRepository->hasUpdatedSince($triggeredAt)) {
            return;
        }

        /** @var CalloutInterface[] $callouts */
        $callouts = $this->calloutRepository->findAll();
        foreach ($callouts as $callout) {
            $callout->setRulesAssignedAt($triggeredAt);
        }
        $this->calloutManager->flush();

        $qb = $this->productRepository->createQueryBuilder('o');

        $batcherFactory = new BestIdRangeBatcherFactory();
        $batcher = $batcherFactory->create($qb);

        foreach ($batcher->getBatches() as $batch) {
            $this->messageBus->dispatch(new AssignEligibleCalloutsToProductsBatch($batch));
        }
    }
}
