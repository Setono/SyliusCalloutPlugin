<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Page\Admin\ProductCallout;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Sylius\Behat\Service\AutocompleteHelper;
use Webmozart\Assert\Assert;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function fillPriority(string $priority): void
    {
        $this->getDocument()->fillField('Priority', $priority);
    }

    public function fillClasses(string $classes): void
    {
        $this->getDocument()->fillField('Classes', $classes);
    }

    public function fillText(string $text): void
    {
        $this->getDocument()->fillField('Text', $text);
    }

    public function fillPosition(string $position): void
    {
        $this->getDocument()->selectFieldOption('Position', $position);
    }

    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function addRule(string $ruleName): void
    {
        $count = count($this->getCollectionItems('rules'));

        $this->getDocument()->clickLink('Add');

        $this->getDocument()->waitFor(5, function () use ($count) {
            return $count + 1 === count($this->getCollectionItems('rules'));
        });

        $this->selectRuleOption('Type', $ruleName);
    }

    public function selectAutocompleteRuleOption(string $option, array|string $value): void
    {
        $option = strtolower(str_replace(' ', '_', $option));

        $ruleAutocomplete = $this
            ->getLastCollectionItem('rules')
            ->find('css', sprintf('input[type="hidden"][name*="[%s]"]', $option))
            ?->getParent()
        ;

        Assert::notNull($ruleAutocomplete, sprintf('Could not find autocomplete for option "%s"', $option));

        if (is_array($value)) {
            AutocompleteHelper::chooseValues($this->getSession(), $ruleAutocomplete, $value);
        } else {
            AutocompleteHelper::chooseValue($this->getSession(), $ruleAutocomplete, $value);
        }
    }

    public function selectRuleOption(string $option, string $value, bool $multiple = false): void
    {
        $this->getLastCollectionItem('rules')->find('named', ['select', $option])?->selectOption($value, $multiple);
    }

    protected function getDefinedElements(): array
    {
        return [
            'rules' => '#callout_rules',
        ];
    }

    /**
     * @return NodeElement[]
     */
    private function getCollectionItems(string $collection): array
    {
        return $this->getElement($collection)->findAll('css', 'div[data-form-collection="item"]');
    }

    private function getLastCollectionItem(string $collection): NodeElement
    {
        $items = $this->getCollectionItems($collection);

        Assert::notEmpty($items);

        return end($items);
    }
}
