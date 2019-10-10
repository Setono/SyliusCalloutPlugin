<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout\Checker\Rule;

use DateTime;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\IsNewProductCalloutRuleChecker;
use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class IsNewProductCalloutRuleCheckerSpec extends ObjectBehavior
{
    public function let(): void
    {
        $now = new DateTime('now');
        $this->beConstructedWith($now);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(IsNewProductCalloutRuleChecker::class);
    }

    public function it_implements_rule_checker_interface(): void
    {
        $this->shouldHaveType(ProductCalloutRuleCheckerInterface::class);
    }

    public function it_has_type(): void
    {
        Assert::eq('is_new', IsNewProductCalloutRuleChecker::TYPE);
    }

    public function it_throws_an_exception_if_the_configuration_key_does_not_exist(ProductInterface $product): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('isEligible', [
            $product,
            ['configuration' => ['taxons' => ['t_shirt_nike', 'mug_lorem_ipsum']]],
        ]);
    }

    public function it_throws_an_exception_if_the_configuration_value_is_not_numeric(ProductInterface $product): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('isEligible', [
            $product,
            ['configuration' => ['days' => 'bla']],
        ]);
    }

    public function it_not_applies_to_products_without_created_at(
        ProductInterface $product
    ): void {
        $product->getCreatedAt()->willReturn(null);

        $this->isEligible($product, ['days' => 1])->shouldReturn(false);
    }

    public function it_not_applies_to_old_products(
        ProductInterface $product
    ): void {
        $now = new DateTime('now');
        $createdAt = new DateTime('-2 days');

        $this->beConstructedWith($now);
        $product->getCreatedAt()->willReturn($createdAt);

        $this->isEligible($product, ['days' => 1])->shouldReturn(false);
    }

    public function it_applies_to_new_products(
        ProductInterface $product
    ): void {
        $now = new DateTime('now');
        $createdAt = new DateTime('-1 days');

        $this->beConstructedWith($now);
        $product->getCreatedAt()->willReturn($createdAt);

        $this->isEligible($product, ['days' => 1])->shouldReturn(true);
    }
}
