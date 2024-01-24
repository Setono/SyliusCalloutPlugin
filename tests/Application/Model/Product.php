<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Application\Model;

use Doctrine\ORM\Mapping as ORM;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Model\ProductTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct implements ProductInterface
{
    use ProductTrait;
}
