<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutsPlugin\Behat\Page\Admin\Callout;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function fillPriority(string $priority): void {
        $this->getDocument()->fillField('Priority', $priority);
    }

    public function fillClasses(string $classes): void {
        $this->getDocument()->fillField('Classes', $classes);
    }

    public function fillText(string $text): void {
        $this->getDocument()->fillField('Text', $text);
    }

    public function fillPosition(string $position): void {
        $this->getDocument()->fillField('Position', $position);
    }

    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }
}
