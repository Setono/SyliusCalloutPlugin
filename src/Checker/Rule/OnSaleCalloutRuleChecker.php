<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class OnSaleCalloutRuleChecker implements CalloutRuleCheckerInterface
{
    public const TYPE = 'on_sale';

    private ChannelContextInterface $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function isEligible(ProductInterface $product, array $configuration): bool
    {
        try {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();
        } catch (ChannelNotFoundException $exception) {
            // If we can not get the channel, simply say that the product is not eligible
            return false;
        }

        /** @var Collection<array-key, ProductVariantInterface> $variants */
        $variants = $product->getVariants();
        if ($variants->isEmpty()) {
            return false;
        }

        foreach ($variants as $variant) {
            $channelPricing = $variant->getChannelPricingForChannel($channel);
            // Ignore if there is no channel pricing
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
