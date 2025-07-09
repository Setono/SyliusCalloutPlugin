<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Factory;

use Setono\SyliusCalloutPlugin\Checker\Rule\HasProductCalloutRuleChecker;
use Setono\SyliusCalloutPlugin\Checker\Rule\HasTaxonCalloutRuleChecker;
use Setono\SyliusCalloutPlugin\Checker\Rule\IsNewCalloutRuleChecker;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class CalloutRuleFactory implements CalloutRuleFactoryInterface
{
    public function __construct(private readonly FactoryInterface $decoratedFactory)
    {
    }

    public function createHasTaxon(array $taxons): CalloutRuleInterface
    {
        return $this->createCalloutRule(HasTaxonCalloutRuleChecker::TYPE, ['taxons' => array_map(static fn (TaxonInterface $taxon): ?string => $taxon->getCode(), $taxons)]);
    }

    public function createHasProduct(array $products): CalloutRuleInterface
    {
        return $this->createCalloutRule(HasProductCalloutRuleChecker::TYPE, ['products' => array_map(static fn (ProductInterface $product): ?string => $product->getCode(), $products)]);
    }

    public function createIsNewProduct(int $days): CalloutRuleInterface
    {
        return $this->createCalloutRule(IsNewCalloutRuleChecker::TYPE, ['days' => $days]);
    }

    public function createNew(): CalloutRuleInterface
    {
        /** @var CalloutRuleInterface $rule */
        $rule = $this->decoratedFactory->createNew();

        return $rule;
    }

    private function createCalloutRule(string $type, array $configuration): CalloutRuleInterface
    {
        $rule = $this->createNew();

        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
    }
}
