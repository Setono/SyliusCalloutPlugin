<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Application\Model;

use Doctrine\ORM\Mapping as ORM;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareTrait as SetonoSyliusCalloutCalloutsAwareTrait;
use Setono\SyliusCalloutPlugin\Model\ProductInterface as SetonoSyliusCalloutProductInterface;
use Sylius\Component\Core\Model\Product as BaseProduct;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct implements SetonoSyliusCalloutProductInterface
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
