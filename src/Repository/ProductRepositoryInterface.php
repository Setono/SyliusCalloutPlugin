<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use Setono\DoctrineORMBatcher\Repository\BatchableInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface as BaseProductRepository;

interface ProductRepositoryInterface extends BaseProductRepository, BatchableInterface
{
}
