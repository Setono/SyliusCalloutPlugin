<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Factory;

use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<CalloutRuleInterface>
 */
interface CalloutRuleFactoryInterface extends FactoryInterface
{
    public function createHasTaxon(array $taxons): CalloutRuleInterface;

    public function createHasProduct(array $products): CalloutRuleInterface;

    public function createIsNewProduct(int $days): CalloutRuleInterface;
}
