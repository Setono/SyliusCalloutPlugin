<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Setono\SyliusCalloutPlugin\Checker\ProductPromotionCheckerInterface;
use Setono\SyliusCalloutPlugin\Exception\UnsupportedTypeException;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class IsOnSaleRuleChecker implements ProductCalloutRuleCheckerInterface
{
    public const TYPE = 'is_on_sale';

    /** @var ProductPromotionCheckerInterface */
    private $productPromotionChecker;

    public function __construct(ProductPromotionCheckerInterface $productPromotionChecker)
    {
        $this->productPromotionChecker = $productPromotionChecker;
    }

    public function isEligible(CalloutsAwareInterface $product, array $configuration): bool
    {
        if (!$product instanceof ProductInterface) {
            throw new UnsupportedTypeException($product, ProductInterface::class);
        }

        Assert::keyExists($configuration, 'isOnSale');
        Assert::boolean($configuration['isOnSale']);

        return $configuration['isOnSale'] === $this->productPromotionChecker->isOnPromotion($product);
    }
}
