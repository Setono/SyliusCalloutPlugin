<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Provider;

use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;

interface CalloutProviderInterface
{
    /**
     * @param CalloutsAwareInterface $product
     *
     * @return CalloutInterface[]
     */
    public function getCallouts(CalloutsAwareInterface $product): array;
}
