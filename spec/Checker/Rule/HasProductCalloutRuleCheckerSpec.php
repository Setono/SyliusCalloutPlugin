<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Checker\Rule;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Checker\Rule\HasProductCalloutRuleChecker;
use Setono\SyliusCalloutsPlugin\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

final class HasProductCalloutRuleCheckerSpec extends ObjectBehavior
{
    function let(ProductRepositoryInterface $productRepository): void
    {
        $this->beConstructedWith($productRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(HasProductCalloutRuleChecker::class);
    }

    function it_implements_rule_checker_interface(): void
    {
        $this->shouldHaveType(ProductCalloutRuleCheckerInterface::class);
    }

    function it_has_type(): void
    {
        Assert::eq('has_product', HasProductCalloutRuleChecker::TYPE);
    }

    function it_throws_an_exception_if_the_configuration_key_does_not_exist(CalloutsAwareInterface $product): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('isEligible', [
            $product,
            ['configuration' => ['taxons' => ['t_shirt_nike', 'mug_lorem_ipsum']]],
        ]);
    }

    function it_applies_to_configured_products(
        ProductRepositoryInterface $productRepository,
        CalloutsAwareInterface $product
    ): void {
        $productRepository->findBy(['code' => ['t_shirt_nike']])->willReturn([$product]);

        $this->isEligible($product, ['products' => ['t_shirt_nike']])->shouldReturn(true);
    }

    function it_applies_to_not_configured_products(
        ProductRepositoryInterface $productRepository,
        CalloutsAwareInterface $configuredProduct,
        CalloutsAwareInterface $product
    ): void {
        $productRepository->findBy(['code' => ['t_shirt_nike']])->willReturn([$configuredProduct]);

        $this->isEligible($product, ['products' => ['t_shirt_nike']])->shouldReturn(false);
    }
}
