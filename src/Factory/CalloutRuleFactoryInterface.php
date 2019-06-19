<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Factory;

use Setono\SyliusCalloutsPlugin\Model\CalloutRuleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface CalloutRuleFactoryInterface extends FactoryInterface
{
    public function createHasTaxon(array $taxons): CalloutRuleInterface;
}
