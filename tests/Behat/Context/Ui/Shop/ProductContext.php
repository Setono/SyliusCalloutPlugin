<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Repository\CalloutRepositoryInterface;
use Tests\Setono\SyliusCalloutsPlugin\Behat\Page\Shop\Product\IndexPageInterface;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    /** @var IndexPageInterface */
    private $indexPage;

    /** @var CalloutRepositoryInterface */
    private $calloutRepository;

    public function __construct(
        IndexPageInterface $indexPage,
        CalloutRepositoryInterface $calloutRepository
    ) {
        $this->indexPage = $indexPage;
        $this->calloutRepository = $calloutRepository;
    }

    /**
     * @Then I should see :count products with product callout :name
     */
    public function iShouldSeeProductsWithProductCallout(int $count, string $name)
    {
        /** @var CalloutInterface $callout */
        $callout = $this->calloutRepository->findOneBy(['code' => md5($name)]);

        Assert::same($count, $this->indexPage->countProductsWithCallouts(strip_tags($callout->getHtml())));
    }
}
