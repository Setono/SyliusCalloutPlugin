<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout\Checker\Rule;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\HasProductCalloutRuleChecker;
use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

final class HasProductCalloutRuleCheckerSpec extends ObjectBehavior
{
    public function let(ProductRepositoryInterface $productRepository): void
    {
        $this->beConstructedWith($productRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(HasProductCalloutRuleChecker::class);
    }

    public function it_implements_rule_checker_interface(): void
    {
        $this->shouldHaveType(ProductCalloutRuleCheckerInterface::class);
    }

    public function it_has_type(): void
    {
        Assert::eq('has_product', HasProductCalloutRuleChecker::TYPE);
    }

    public function it_throws_an_exception_if_the_configuration_key_does_not_exist(ProductInterface $product): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('isEligible', [
            $product,
            ['configuration' => ['taxons' => ['t_shirt_nike', 'mug_lorem_ipsum']]],
        ]);
    }

    public function it_applies_to_configured_products(
        ProductRepositoryInterface $productRepository,
        ProductInterface $product
    ): void {
        $productRepository->findBy(['code' => ['t_shirt_nike']])->willReturn([$product]);

        $this->isEligible($product, ['products' => ['t_shirt_nike']])->shouldReturn(true);
    }

    public function it_applies_to_not_configured_products(
        ProductRepositoryInterface $productRepository,
        ProductInterface $configuredProduct,
        ProductInterface $product
    ): void {
        $productRepository->findBy(['code' => ['t_shirt_nike']])->willReturn([$configuredProduct]);

        $this->isEligible($product, ['products' => ['t_shirt_nike']])->shouldReturn(false);
    }
}
