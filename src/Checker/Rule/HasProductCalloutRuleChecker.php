<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

final class HasProductCalloutRuleChecker implements CalloutRuleCheckerInterface
{
    public const TYPE = 'has_product';

    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function isEligible(ProductInterface $product, array $configuration): bool
    {
        Assert::keyExists($configuration, 'products');

        /** @var ProductInterface[] $configuredProducts */
        $configuredProducts = $this->productRepository->findBy(['code' => $configuration['products']]);

        return in_array($product, $configuredProducts, true);
    }
}
