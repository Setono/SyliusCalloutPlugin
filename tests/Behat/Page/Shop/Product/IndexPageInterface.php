<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Page\Shop\Product;

use Sylius\Behat\Page\Shop\Product\IndexPageInterface as BaseIndexPageInterface;

interface IndexPageInterface extends BaseIndexPageInterface
{
    public function countProductsWithCallouts(string $content): int;
}
