<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use function Safe\sprintf;
use Setono\SyliusCalloutPlugin\Message\Command\AssignProductCallouts;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Provider\CalloutProviderInterface;
use Setono\SyliusCalloutPlugin\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AssignProductCalloutsHandler implements MessageHandlerInterface
{
    /** @var CalloutProviderInterface */
    private $calloutProvider;

    /** @var EntityManagerInterface */
    private $productManager;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        CalloutProviderInterface $calloutProvider,
        EntityManagerInterface $productManager,
        ProductRepositoryInterface $productRepository
    ) {
        $this->calloutProvider = $calloutProvider;
        $this->productManager = $productManager;
        $this->productRepository = $productRepository;
    }

    public function __invoke(AssignProductCallouts $message): void
    {
        /** @var ProductInterface[] $products */
        $products = $this->productRepository->getBatch($message->getBatch(), static function (QueryBuilder $qb, string $alias) {
            $qb->andWhere(sprintf('%s.enabled = 1', $alias));
        });

        if (count($products) === 0) {
            return;
        }

        foreach ($products as $product) {
            $callouts = $this->calloutProvider->getCallouts($product);

            $product->setCallouts($callouts);
        }

        $this->productManager->flush();
    }
}
