<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Page\Admin\ProductCallout;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function fillPriority(string $priority): void;

    public function fillClasses(string $classes): void;

    public function fillText(string $text): void;

    public function fillPosition(string $position): void;

    public function fillCode(string $code): void;

    public function fillName(string $name): void;

    public function addRule(string $ruleName): void;

    /**
     * @param string[]|string $value
     */
    public function selectAutocompleteRuleOption(string $option, array|string $value): void;
}
