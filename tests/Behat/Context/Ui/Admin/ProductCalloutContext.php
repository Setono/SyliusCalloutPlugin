<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Tests\Setono\SyliusCalloutPlugin\Behat\Page\Admin\ProductCallout\CreatePageInterface;

final class ProductCalloutContext implements Context
{
    public function __construct(
        private readonly CreatePageInterface $createPage,
        private readonly NotificationCheckerInterface $notificationChecker,
    ) {
    }

    /**
     * @When I go to the create product callout page
     */
    public function iGoToTheCreateCalloutPage(): void
    {
        $this->createPage->open();
    }

    /**
     * @When I fill the priority with :priority
     */
    public function iFillThePriorityWith(string $priority): void
    {
        $this->createPage->fillPriority($priority);
    }

    /**
     * @When I fill the classes with :classes
     */
    public function iFillTheClassesWith(string $classes): void
    {
        $this->createPage->fillClasses($classes);
    }

    /**
     * @When I fill the text with :text
     */
    public function iFillTheTextWith(string $text): void
    {
        $this->createPage->fillText($text);
    }

    /**
     * @When I specify its position as :position
     */
    public function iSpecifyItsPositionAs(string $position): void
    {
        $this->createPage->fillPosition($position);
    }

    /**
     * @When I specify its code as :code
     */
    public function iSpecifyItsCodeAs(string $code): void
    {
        $this->createPage->fillCode($code);
    }

    /**
     * @When I name it :name
     */
    public function iNameItIn(string $name): void
    {
        $this->createPage->fillName($name);
    }

    /**
     * @When I add the "Has taxon" rule configured with :arg2 and :arg3
     */
    public function iAddTheRuleConfiguredWithAnd(string ...$taxons): void
    {
        $this->createPage->addRule('Has taxon');

        $this->createPage->selectAutocompleteRuleOption('taxons', $taxons);
    }

    /**
     * @When I add the "Has product" rule configured with the :productName product
     */
    public function iAddTheRuleConfiguredWithTheProduct(string $productName): void
    {
        $this->createPage->addRule('Has product');

        $this->createPage->selectAutocompleteRuleOption('products', $productName);
    }

    /**
     * @When I add it
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @Then I should be notified that it has been successfully created
     */
    public function iShouldBeNotifiedThatItHasBeenSuccessfullyCreated(): void
    {
        $this->notificationChecker->checkNotification('has been successfully created.', NotificationType::success());
    }
}
