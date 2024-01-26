<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;

final class OnSaleCalloutRuleChecker implements CalloutRuleCheckerInterface
{
    public const TYPE = 'on_sale';

    private ChannelContextInterface $channelContext;

    public function __construct(
        ChannelContextInterface $channelContext,
        private readonly ProductVariantResolverInterface $productVariantResolver,
    ) {
        $this->channelContext = $channelContext;
    }

    public function isEligible(ProductInterface $product, array $configuration): bool
    {
        return true;
    }

    public function isRuntimeEligible(ProductInterface $product, array $configuration): bool
    {
        try {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();
        } catch (ChannelNotFoundException) {
            // If we can not get the channel, simply say that the product is not eligible
            return false;
        }

        $singleVariantEligible = isset($configuration['singleVariantEligible']) && true === $configuration['singleVariantEligible'];

        if ($singleVariantEligible) {
            /** @var ProductVariantInterface|null $variant */
            $variant = $this->productVariantResolver->getVariant($product);
            if (null === $variant) {
                return false;
            }

            $variants = [$variant];
        } else {
            /** @var list<ProductVariantInterface> $variants */
            $variants = $product->getVariants()->toArray();

            if ([] === $variants) {
                return false;
            }
        }

        foreach ($variants as $variant) {
            $channelPricing = $variant->getChannelPricingForChannel($channel);
            if (null === $channelPricing) {
                continue;
            }

            $originalPrice = $channelPricing->getOriginalPrice();
            if (null === $originalPrice || $originalPrice <= $channelPricing->getPrice()) {
                return false;
            }
        }

        return true;
    }
}
