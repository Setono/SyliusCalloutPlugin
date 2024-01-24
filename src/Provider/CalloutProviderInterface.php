<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Provider;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

interface CalloutProviderInterface
{
    /**
     * Will return a list of callouts from the given callout codes
     *
     * @param list<string> $codes
     *
     * @return list<CalloutInterface>
     */
    public function getByCodes(array $codes): array;
}
