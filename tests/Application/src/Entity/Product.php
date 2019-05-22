<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutsPlugin\Entity;

use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;

final class Product extends BaseProduct implements ProductInterface
{
    use CalloutsAwareTrait;
}
