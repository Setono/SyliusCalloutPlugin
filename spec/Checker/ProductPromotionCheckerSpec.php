<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Checker;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Checker\ProductPromotionChecker;
use Setono\SyliusCalloutsPlugin\Checker\ProductPromotionCheckerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Promotion\Model\PromotionRuleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class ProductPromotionCheckerSpec extends ObjectBehavior
{
    function let(RepositoryInterface $promotionRuleRepository, TaxonRepositoryInterface $taxonRepository): void
    {
        $this->beConstructedWith($promotionRuleRepository, $taxonRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductPromotionChecker::class);
    }

    function it_implements_product_promotion_checker_interface(): void
    {
        $this->shouldHaveType(ProductPromotionCheckerInterface::class);
    }

    function it_passes_if_the_product_has_applicable_product_promotion(
        RepositoryInterface $promotionRuleRepository,
        PromotionRuleInterface $promotionRule,
        ProductInterface $product
    ): void {
        $promotionRuleRepository->findBy(['type' => 'contains_product'])->willReturn([$promotionRule]);
        $promotionRule->getConfiguration()->willReturn(['products' => ['nike_tshirt']]);
        $product->getCode()->willReturn('nike_tshirt');

        $this->isOnPromotion($product)->shouldReturn(true);
    }

    function it_fails_if_the_product_has_not_an_applicable_product_promotion(
        RepositoryInterface $promotionRuleRepository,
        PromotionRuleInterface $productsPromotionRule,
        ProductInterface $product
    ): void {
        $promotionRuleRepository->findBy(['type' => 'contains_product'])->willReturn([$productsPromotionRule]);
        $productsPromotionRule->getConfiguration()->willReturn(['products' => ['nike_tshirt']]);
        $product->getCode()->willReturn('puma_tshirt');
        $promotionRuleRepository->findBy(['type' => 'has_taxon'])->willReturn([]);

        $this->isOnPromotion($product)->shouldReturn(false);
    }

    function it_passes_if_the_product_has_applicable_taxon_promotion(
        RepositoryInterface $promotionRuleRepository,
        PromotionRuleInterface $productsPromotionRule,
        PromotionRuleInterface $taxonsPromotionRule,
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        ProductInterface $product
    ): void {
        $promotionRuleRepository->findBy(['type' => 'contains_product'])->willReturn([$productsPromotionRule]);
        $productsPromotionRule->getConfiguration()->willReturn(['products' => ['nike_tshirt']]);
        $product->getCode()->willReturn('puma_tshirt');
        $promotionRuleRepository->findBy(['type' => 'has_taxon'])->willReturn([$taxonsPromotionRule]);
        $taxonsPromotionRule->getConfiguration()->willReturn(['taxons' => ['tshirts']]);
        $taxonRepository->findOneBy(['code' => 'tshirts'])->willReturn($taxon);
        $product->hasTaxon($taxon)->willReturn(true);

        $this->isOnPromotion($product)->shouldReturn(true);
    }

    function it_fails_if_the_product_has_not_an_applicable_taxon_promotion(
        RepositoryInterface $promotionRuleRepository,
        PromotionRuleInterface $productsPromotionRule,
        PromotionRuleInterface $taxonsPromotionRule,
        TaxonRepositoryInterface $taxonRepository,
        TaxonInterface $taxon,
        ProductInterface $product
    ): void {
        $promotionRuleRepository->findBy(['type' => 'contains_product'])->willReturn([$productsPromotionRule]);
        $productsPromotionRule->getConfiguration()->willReturn(['products' => ['nike_tshirt']]);
        $product->getCode()->willReturn('puma_tshirt');
        $promotionRuleRepository->findBy(['type' => 'has_taxon'])->willReturn([$taxonsPromotionRule]);
        $taxonsPromotionRule->getConfiguration()->willReturn(['taxons' => ['tshirts']]);
        $taxonRepository->findOneBy(['code' => 'tshirts'])->willReturn($taxon);
        $product->hasTaxon($taxon)->willReturn(false);

        $this->isOnPromotion($product)->shouldReturn(false);
    }
}
