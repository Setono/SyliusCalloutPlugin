<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use Sylius\Component\Core\Model\ProductInterface as BaseProductInterface;

interface ProductInterface extends BaseProductInterface
{
    /**
     * This method should return a list of callout codes that the product is pre-qualified for.
     * Pre-qualified means that the product matches the respective callouts rules
     *
     * @return list<string>
     */
    public function getPreQualifiedCallouts(): array;

    public function addPreQualifiedCallout(CalloutInterface|string $preQualifiedCallout): void;

    public function removePreQualifiedCallout(string $preQualifiedCallout): void;

    /**
     * Resets the pre-qualified callouts
     */
    public function resetPreQualifiedCallouts(): void;
}
