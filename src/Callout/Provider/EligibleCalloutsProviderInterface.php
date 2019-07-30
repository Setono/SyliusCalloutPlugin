<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Provider;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;

interface EligibleCalloutsProviderInterface
{
    /**
     * @return CalloutInterface[]
     */
    public function getEligibleCallouts(CalloutsAwareInterface $product): array;
}
