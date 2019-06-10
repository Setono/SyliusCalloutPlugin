<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Factory;

use Setono\SyliusCalloutsPlugin\Checker\Rule\HasTaxonCalloutRuleChecker;
use Setono\SyliusCalloutsPlugin\Model\CalloutRule;
use Setono\SyliusCalloutsPlugin\Model\CalloutRuleInterface;
use Sylius\Component\Core\Model\TaxonInterface;

class CalloutRuleFactory implements CalloutRuleFactoryInterface
{
    public function createHasTaxon(array $taxons): CalloutRuleInterface
    {
        return $this->createCalloutRule(HasTaxonCalloutRuleChecker::TYPE, ['taxons' => array_map(function (TaxonInterface $taxon) {
            return $taxon->getCode();
        }, $taxons)]);
    }

    public function createNew(): CalloutRuleInterface
    {
        return new CalloutRule();
    }

    private function createCalloutRule(string $type, array $configuration): CalloutRuleInterface
    {
        $rule = $this->createNew();

        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
    }
}
