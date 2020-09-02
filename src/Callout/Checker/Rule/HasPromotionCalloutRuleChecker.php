<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\Rule;

use Doctrine\Common\Collections\Collection;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Promotion\Exception\UnsupportedTypeException;

final class HasPromotionCalloutRuleChecker implements ProductCalloutRuleCheckerInterface
{
    public const TYPE = 'has_promotion';

    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function isEligible(CalloutsAwareInterface $product, array $configuration): bool
    {
        if (!$product instanceof ProductInterface) {
            throw new UnsupportedTypeException($product, ProductInterface::class);
        }
        try {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();
        } catch (ChannelNotFoundException $exception) {
            // If we can not get the channel, simply say that the product is not eligible
            return false;
        }

        /** @var ProductVariantInterface[]|Collection $variants */
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
