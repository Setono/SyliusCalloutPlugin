<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Provider;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;

interface CalloutProviderInterface
{
    /**
     * @return CalloutInterface[]
     */
    public function getCallouts(CalloutsAwareInterface $product): array;
}
