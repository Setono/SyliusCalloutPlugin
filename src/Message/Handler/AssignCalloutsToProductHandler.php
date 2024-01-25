<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityRepository;
use Setono\SyliusCalloutPlugin\Message\Command\AssignCalloutsToProduct;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Webmozart\Assert\Assert;

final class AssignCalloutsToProductHandler extends AbstractAssignCalloutsHandler
{
    public function __invoke(AssignCalloutsToProduct $message): void
    {
        $manager = $this->getManager($this->productClass);

        /** @var ProductRepositoryInterface|EntityRepository $productRepository */
        $productRepository = $manager->getRepository($this->productClass);
        Assert::isInstanceOf($productRepository, ProductRepositoryInterface::class);

        $product = $productRepository->findOneByCode($message->product);
        if (!$product instanceof ProductInterface) {
            throw new UnrecoverableMessageHandlingException(sprintf('Product with code "%s" does not exist', $message->product));
        }

        $products = [$product];

        $this->assign($products);
    }
}
