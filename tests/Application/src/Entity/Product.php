<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Entity;

use Setono\SyliusCalloutPlugin\Model\CalloutsAwareTrait as SetonoSyliusCalloutCalloutsAwareTrait;
use Setono\SyliusCalloutPlugin\Model\ProductInterface as SetonoSyliusCalloutProductInterface;
use Sylius\Component\Core\Model\Product as BaseProduct;

final class Product extends BaseProduct implements SetonoSyliusCalloutProductInterface
{
    use SetonoSyliusCalloutCalloutsAwareTrait {
        SetonoSyliusCalloutCalloutsAwareTrait::__construct as private __calloutsTraitConstruct;
    }

    public function __construct()
    {
        $this->__calloutsTraitConstruct();
        parent::__construct();
    }
}
