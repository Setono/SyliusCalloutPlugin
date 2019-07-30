<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Provider;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

interface PreQualifiedCalloutsProviderInterface
{
    /**
     * @return CalloutInterface[]
     */
    public function getCallouts(): array;
}
