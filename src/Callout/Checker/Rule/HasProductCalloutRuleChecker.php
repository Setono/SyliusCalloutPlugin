<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\Rule;

use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Promotion\Exception\UnsupportedTypeException;
use Webmozart\Assert\Assert;

final class HasProductCalloutRuleChecker implements ProductCalloutRuleCheckerInterface
{
    public const TYPE = 'has_product';

    /** @var ProductRepositoryInterface */
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function isEligible(CalloutsAwareInterface $product, array $configuration): bool
    {
        if (!$product instanceof ProductInterface) {
            throw new UnsupportedTypeException($product, ProductInterface::class);
        }

        Assert::keyExists($configuration, 'products');

        /** @var ProductInterface[] $configuredProducts */
        $configuredProducts = $this->productRepository->findBy(['code' => $configuration['products']]);

        return in_array($product, $configuredProducts, true);
    }
}
