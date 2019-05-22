<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Checker\Rule;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Checker\Rule\HasTaxonCalloutRuleChecker;
use Setono\SyliusCalloutsPlugin\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
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

    function it_throws_an_exception_if_the_configuration_key_does_not_exist(CalloutsAwareInterface $taxon): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('isEligible', [
            $taxon,
            ['configuration' => ['taxons' => ['mugs', 't_shits']]],
        ]);
    }

    function it_applies_to_configured_taxons(
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        CalloutsAwareInterface $product
    ): void {
        $taxonRepository->findBy(['code' => ['mugs']])->willReturn([$taxon]);
        $product->hasTaxon($taxon)->willReturn(true);

        $this->isEligible($product, ['taxons' => ['mugs']])->shouldReturn(true);
    }

    function it_applies_to_not_configured_taxons(
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        CalloutsAwareInterface $product
    ): void {
        $taxonRepository->findBy(['code' => ['mugs']])->willReturn([$taxon]);
        $product->hasTaxon($taxon)->willReturn(false);

        $this->isEligible($product, ['taxons' => ['mugs']])->shouldReturn(false);
    }
}
