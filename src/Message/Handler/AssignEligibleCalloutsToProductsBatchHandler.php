<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineORMBatcher\Query\QueryRebuilder;
use Setono\SyliusCalloutPlugin\Callout\Provider\EligibleCalloutsProviderInterface;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProductsBatch;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AssignEligibleCalloutsToProductsBatchHandler implements MessageHandlerInterface
{
    private EligibleCalloutsProviderInterface $eligibleCalloutsProvider;

    private EntityManagerInterface $productManager;

    private ManagerRegistry $managerRegistry;

    public function __construct(
        EligibleCalloutsProviderInterface $eligibleCalloutsProvider,
        EntityManagerInterface $productManager,
        ManagerRegistry $managerRegistry
    ) {
        $this->eligibleCalloutsProvider = $eligibleCalloutsProvider;
        $this->productManager = $productManager;
        $this->managerRegistry = $managerRegistry;
    }

    public function __invoke(AssignEligibleCalloutsToProductsBatch $message): void
    {
        $queryRebuilder = new QueryRebuilder($this->managerRegistry);
        $query = $queryRebuilder->rebuild($message->getBatch());

        /** @var ProductInterface[] $products */
        $products = $query->getResult();
        if (count($products) === 0) {
            return;
        }

        foreach ($products as $product) {
            $product->setCallouts(
                $this->eligibleCalloutsProvider->getEligibleCallouts($product)
            );
        }

        $this->productManager->flush();
    }
}
