<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProduct;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Provider\CalloutProviderInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class AssignEligibleCalloutsToProductHandler implements MessageHandlerInterface
{
    /** @var CalloutProviderInterface */
    private $calloutProvider;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ObjectManager */
    private $productManager;

    public function __construct(
        CalloutProviderInterface $calloutProvider,
        ProductRepositoryInterface $productRepository,
        ObjectManager $productManager
    ) {
        $this->calloutProvider = $calloutProvider;
        $this->productRepository = $productRepository;
        $this->productManager = $productManager;
    }

    public function __invoke(AssignEligibleCalloutsToProduct $message): void
    {
        /** @var ProductInterface|null $product */
        $product = $this->productRepository->find($message->getProductId());

        Assert::isInstanceOf($product, ProductInterface::class);

        $callouts = $this->calloutProvider->getCallouts($product);

        $product->setCallouts($callouts);

        $this->productManager->flush();
    }
}
