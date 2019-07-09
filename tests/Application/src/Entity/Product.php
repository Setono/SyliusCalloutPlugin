<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Entity;

use Setono\SyliusCalloutPlugin\Model\CalloutsAwareTrait as SetonoSyliusCalloutPluginCalloutsAwareTrait;
use Setono\SyliusCalloutPlugin\Model\ProductInterface as SetonoSyliusCalloutPluginCalloutsProductInterface;
use Sylius\Component\Core\Model\Product as BaseProduct;

final class Product extends BaseProduct implements SetonoSyliusCalloutPluginCalloutsProductInterface
{
    use SetonoSyliusCalloutPluginCalloutsAwareTrait {
        SetonoSyliusCalloutPluginCalloutsAwareTrait::__construct as private __calloutsTraitConstruct;
    }

    public function __construct()
    {
        $this->__calloutsTraitConstruct();
        parent::__construct();
    }
}
