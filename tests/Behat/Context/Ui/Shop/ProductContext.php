<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Tests\Setono\SyliusCalloutPlugin\Behat\Page\Shop\Product\IndexPageInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    public function __construct(
        private readonly IndexPageInterface $indexPage,
        private readonly CalloutRepositoryInterface $calloutRepository,
    ) {
    }

    /**
     * @Then I should see :count products with callout :name
     * @Then I should see :count product with callout :name
     */
    public function iShouldSeeProductsWithProductCallout(int $count, string $name): void
    {
        $callout = $this->calloutRepository->findOneByCode(StringInflector::nameToCode($name));

        Assert::notNull($callout);
        Assert::same($this->indexPage->countProductsWithCallouts(strip_tags((string) $callout->getText())), $count);
    }
}
