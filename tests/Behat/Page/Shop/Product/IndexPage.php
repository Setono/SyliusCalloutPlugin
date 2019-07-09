<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Page\Shop\Product;

use Sylius\Behat\Page\Shop\Product\IndexPage as BaseIndexPage;

class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    public function countProductsWithCallouts(string $content): int
    {
        return count($this->getDocument()->findAll('css', sprintf('.setono-callout:contains("%s")', $content)));
    }
}
