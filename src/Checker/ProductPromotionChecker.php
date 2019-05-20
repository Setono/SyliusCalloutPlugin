<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Checker;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Promotion\Checker\Rule\ContainsProductRuleChecker;
use Sylius\Component\Core\Promotion\Checker\Rule\HasTaxonRuleChecker;
use Sylius\Component\Promotion\Model\PromotionRuleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

final class ProductPromotionChecker implements ProductPromotionCheckerInterface
{
    /** @var RepositoryInterface */
    private $promotionRuleRepository;

    /** @var TaxonRepositoryInterface */
    private $taxonRepository;

    public function __construct(RepositoryInterface $promotionRuleRepository, TaxonRepositoryInterface $taxonRepository)
    {
        $this->promotionRuleRepository = $promotionRuleRepository;
        $this->taxonRepository = $taxonRepository;
    }

    public function isOnPromotion(ProductInterface $product): bool
    {
        return $this->hasApplicableProductPromotion($product) || $this->hasApplicableTaxonPromotion($product);
    }

    private function hasApplicableProductPromotion(ProductInterface $product): bool
    {
        /** @var PromotionRuleInterface[] $rules */
        $rules = $this->promotionRuleRepository->findBy(['type' => ContainsProductRuleChecker::TYPE]);

        foreach ($rules as $rule) {
            Assert::keyExists($rule->getConfiguration(), 'products');

            if (in_array($product->getCode(), $rule->getConfiguration()['products'])) {
                return true;
            }
        }

        return false;
    }

    private function hasApplicableTaxonPromotion(ProductInterface $product): bool
    {
        /** @var PromotionRuleInterface[] $rules */
        $rules = $this->promotionRuleRepository->findBy(['type' => HasTaxonRuleChecker::TYPE]);

        foreach ($rules as $rule) {
            Assert::keyExists($rule->getConfiguration(), 'taxons');
            $discountedTaxonCodes = $rule->getConfiguration()['taxons'];

            foreach ($discountedTaxonCodes as $discountedTaxonCode) {
                /** @var TaxonInterface $taxon */
                $taxon = $this->taxonRepository->findOneBy(['code' => $discountedTaxonCode]);

                if ($product->hasTaxon($taxon)) {
                    return true;
                }
            }
        }

        return false;
    }
}
