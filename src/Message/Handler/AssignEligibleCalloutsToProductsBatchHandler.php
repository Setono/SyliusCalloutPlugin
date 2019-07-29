<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Setono\DoctrineORMBatcher\Query\QueryRebuilder;
use Setono\SyliusCalloutPlugin\Callout\Provider\EligibleCalloutsProviderInterface;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProductsBatch;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AssignEligibleCalloutsToProductsBatchHandler implements MessageHandlerInterface
{
    /** @var EligibleCalloutsProviderInterface */
    private $eligibleCalloutsProvider;

    /** @var EntityManagerInterface */
    private $productManager;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        EligibleCalloutsProviderInterface $eligibleCalloutsProvider,
        EntityManagerInterface $productManager,
        ProductRepositoryInterface $productRepository
    ) {
        $this->eligibleCalloutsProvider = $eligibleCalloutsProvider;
        $this->productManager = $productManager;
        $this->productRepository = $productRepository;
    }

    public function __invoke(AssignEligibleCalloutsToProductsBatch $message): void
    {
        if (!$this->productRepository instanceof EntityRepository) {
            return;
        }

        $queryRebuilder = new QueryRebuilder();
        $query = $queryRebuilder->rebuild($this->productManager, $message->getBatch());

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
