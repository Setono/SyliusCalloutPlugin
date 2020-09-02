<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout\Checker\Rule;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\HasPromotionCalloutRuleChecker;
use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Webmozart\Assert\Assert;

final class HasPromotionCalloutRuleCheckerSpec extends ObjectBehavior
{
    public function let(ChannelContextInterface $channelContext): void
    {
        $this->beConstructedWith($channelContext);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(HasPromotionCalloutRuleChecker::class);
    }

    public function it_implements_rule_checker_interface(): void
    {
        $this->shouldHaveType(ProductCalloutRuleCheckerInterface::class);
    }

    public function it_has_type(): void
    {
        Assert::eq('has_promotion', HasPromotionCalloutRuleChecker::TYPE);
    }

    public function it_is_not_eligible_if_channel_has_not_been_found(ChannelContextInterface $channelContext, ProductInterface $product): void
    {
        $channelContext->getChannel()->willThrow(ChannelNotFoundException::class);

        $this->isEligible($product, [])->shouldReturn(false);
    }

    public function it_is_not_eligible_if_product_has_no_variant(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        ProductInterface $product
    ): void {
        $channelContext->getChannel()->willReturn($channel);

        $product->getVariants()->willReturn(new ArrayCollection());

        $this->isEligible($product, [])->shouldReturn(false);
    }

    public function it_is_eligible_by_default_if_no_channel_pricing_is_found(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        ProductInterface $product,
        ProductVariantInterface $productVariant
    ): void {
        $channelContext->getChannel()->willReturn($channel);

        $productVariant->getChannelPricingForChannel($channel)->willReturn(null);
        $product->getVariants()->willReturn(new ArrayCollection([$productVariant->getWrappedObject()]));

        $this->isEligible($product, [])->shouldReturn(true);
    }

    public function it_is_not_eligible_if_at_least_one_variant_is_not_on_sale(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        ProductInterface $product,
        ProductVariantInterface $productVariant1,
        ProductVariantInterface $productVariant2,
        ChannelPricingInterface $channelPricing1,
        ChannelPricingInterface $channelPricing2
    ): void {
        $channelContext->getChannel()->willReturn($channel);

        $productVariant1->getChannelPricingForChannel($channel)->willReturn($channelPricing1);
        $productVariant2->getChannelPricingForChannel($channel)->willReturn($channelPricing2);

        $channelPricing1->getOriginalPrice()->willReturn(15);
        $channelPricing1->getPrice()->willReturn(10);

        $channelPricing2->getOriginalPrice()->willReturn(null);

        $product->getVariants()->willReturn(new ArrayCollection([
            $productVariant1->getWrappedObject(),
            $productVariant2->getWrappedObject(),
        ]));

        $this->isEligible($product, [])->shouldReturn(false);
    }

    public function it_is_eligible_if_all_variants_are_on_sale(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        ProductInterface $product,
        ProductVariantInterface $productVariant1,
        ProductVariantInterface $productVariant2,
        ChannelPricingInterface $channelPricing1,
        ChannelPricingInterface $channelPricing2
    ): void {
        $channelContext->getChannel()->willReturn($channel);

        $productVariant1->getChannelPricingForChannel($channel)->willReturn($channelPricing1);
        $productVariant2->getChannelPricingForChannel($channel)->willReturn($channelPricing2);

        $channelPricing1->getOriginalPrice()->willReturn(15);
        $channelPricing1->getPrice()->willReturn(10);

        $channelPricing2->getOriginalPrice()->willReturn(12);
        $channelPricing2->getPrice()->willReturn(10);

        $product->getVariants()->willReturn(new ArrayCollection([
            $productVariant1->getWrappedObject(),
            $productVariant2->getWrappedObject(),
        ]));

        $this->isEligible($product, [])->shouldReturn(true);
    }
}
