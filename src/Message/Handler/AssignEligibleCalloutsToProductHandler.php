<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\Persistence\ObjectManager;
use Setono\SyliusCalloutPlugin\Callout\Provider\EligibleCalloutsProviderInterface;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProduct;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AssignEligibleCalloutsToProductHandler implements MessageHandlerInterface
{
    /** @var EligibleCalloutsProviderInterface */
    private $eligibleCalloutsProvider;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ObjectManager */
    private $productManager;

    public function __construct(
        EligibleCalloutsProviderInterface $eligibleCalloutsProvider,
        ProductRepositoryInterface $productRepository,
        ObjectManager $productManager
    ) {
        $this->eligibleCalloutsProvider = $eligibleCalloutsProvider;
        $this->productRepository = $productRepository;
        $this->productManager = $productManager;
    }

    public function __invoke(AssignEligibleCalloutsToProduct $message): void
    {
        /** @var ProductInterface|null $product */
        $product = $this->productRepository->find($message->getProductId());
        if (!$product instanceof ProductInterface) {
            return;
        }

        $product->setCallouts(
            $this->eligibleCalloutsProvider->getEligibleCallouts($product)
        );

        // We don't want this here
        // as flushing here causing Doctrine Event Listener
        // fall to deadlock
        // $this->productManager->flush();
    }
}
