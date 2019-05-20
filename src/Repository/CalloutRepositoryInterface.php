<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Repository;

use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface CalloutRepositoryInterface extends RepositoryInterface
{
    /**
     * @return CalloutInterface[]
     */
    public function findActive(): array;
}
