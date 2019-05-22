<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Checker\Rule;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Checker\ProductPromotionCheckerInterface;
use Setono\SyliusCalloutsPlugin\Checker\Rule\IsOnSaleRuleChecker;
use Setono\SyliusCalloutsPlugin\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Webmozart\Assert\Assert;

final class IsOnSaleRuleCheckerSpec extends ObjectBehavior
{
    function let(ProductPromotionCheckerInterface $productPromotionChecker): void
    {
        $this->beConstructedWith($productPromotionChecker);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(IsOnSaleRuleChecker::class);
    }

    function it_implements_rule_checker_interface(): void
    {
        $this->shouldHaveType(ProductCalloutRuleCheckerInterface::class);
    }

    function it_has_type(): void
    {
        Assert::eq('is_on_sale', IsOnSaleRuleChecker::TYPE);
    }

    function it_throws_an_exception_if_configuration_is_not_set_properly(CalloutsAwareInterface $product): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('isEligible', [$product, ['is_on_sale' => true]]);
        $this->shouldThrow(\InvalidArgumentException::class)->during('isEligible', [$product, ['isOnSale' => 1]]);
    }

    function it_checks_eligibility_for_sale_product(
        ProductPromotionCheckerInterface $productPromotionChecker,
        CalloutsAwareInterface $product
    ): void {
        $productPromotionChecker->isOnPromotion($product)->willReturn(true);

        $this->isEligible($product, ['isOnSale' => true])->shouldReturn(true);
        $this->isEligible($product, ['isOnSale' => false])->shouldReturn(false);
    }

    function it_checks_eligibility_for_non_sale_product(
        ProductPromotionCheckerInterface $productPromotionChecker,
        CalloutsAwareInterface $product
    ): void {
        $productPromotionChecker->isOnPromotion($product)->willReturn(false);

        $this->isEligible($product, ['isOnSale' => true])->shouldReturn(false);
        $this->isEligible($product, ['isOnSale' => false])->shouldReturn(true);
    }
}
