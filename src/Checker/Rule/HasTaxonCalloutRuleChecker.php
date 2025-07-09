<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

final class HasTaxonCalloutRuleChecker extends AbstractCalloutRuleChecker
{
    public const TYPE = 'has_taxon';

    public function __construct(private readonly TaxonRepositoryInterface $taxonRepository)
    {
    }

    public function isEligible(ProductInterface $product, array $configuration): bool
    {
        Assert::keyExists($configuration, 'taxons');

        /** @var TaxonInterface[] $taxons */
        $taxons = $this->taxonRepository->findBy(['code' => $configuration['taxons']]);

        foreach ($taxons as $taxon) {
            if ($product->getMainTaxon() === $taxon || $product->hasTaxon($taxon)) {
                return true;
            }
        }

        return false;
    }
}
