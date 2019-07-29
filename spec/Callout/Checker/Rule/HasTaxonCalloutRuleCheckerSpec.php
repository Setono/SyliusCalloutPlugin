<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout\Checker\Rule;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\HasTaxonCalloutRuleChecker;
use Setono\SyliusCalloutPlugin\Callout\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

final class HasTaxonCalloutRuleCheckerSpec extends ObjectBehavior
{
    function let(TaxonRepositoryInterface $taxonRepository): void
    {
        $this->beConstructedWith($taxonRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(HasTaxonCalloutRuleChecker::class);
    }

    function it_implements_rule_checker_interface(): void
    {
        $this->shouldHaveType(ProductCalloutRuleCheckerInterface::class);
    }

    function it_has_type(): void
    {
        Assert::eq('has_taxon', HasTaxonCalloutRuleChecker::TYPE);
    }

    function it_throws_an_exception_if_the_configuration_key_does_not_exist(ProductInterface $taxon): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('isEligible', [
            $taxon,
            ['configuration' => ['taxons' => ['mugs', 't_shits']]],
        ]);
    }

    function it_applies_to_configured_main_taxon(
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        ProductInterface $product
    ): void {
        $taxonRepository->findBy(['code' => ['mugs']])->willReturn([$taxon]);
        $product->getMainTaxon()->willReturn($taxon);
        $product->hasTaxon($taxon)->willReturn(false);

        $this->isEligible($product, ['taxons' => ['mugs']])->shouldReturn(true);
    }

    function it_applies_to_configured_taxons(
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        ProductInterface $product
    ): void {
        $taxonRepository->findBy(['code' => ['mugs']])->willReturn([$taxon]);
        $product->getMainTaxon()->willReturn(null);
        $product->hasTaxon($taxon)->willReturn(true);

        $this->isEligible($product, ['taxons' => ['mugs']])->shouldReturn(true);
    }

    function it_applies_to_not_configured_taxons(
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        ProductInterface $product
    ): void {
        $taxonRepository->findBy(['code' => ['mugs']])->willReturn([$taxon]);
        $product->getMainTaxon()->willReturn(null);
        $product->hasTaxon($taxon)->willReturn(false);

        $this->isEligible($product, ['taxons' => ['mugs']])->shouldReturn(false);
    }
}
