<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use DateTimeInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface CalloutRepositoryInterface extends RepositoryInterface
{
    /**
     * @return CalloutInterface[]
     */
    public function findOrdered(): array;

    public function hasUpdatedSince(DateTimeInterface $updatedSince): bool;

    public function findEligible(?DateTimeInterface $date = null): array;
}
