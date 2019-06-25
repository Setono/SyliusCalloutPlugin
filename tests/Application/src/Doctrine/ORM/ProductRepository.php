<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Doctrine\ORM;

use Setono\DoctrineORMBatcher\Repository\BatchableTrait;
use Setono\SyliusCalloutPlugin\Repository\ProductRepositoryInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;

final class ProductRepository extends BaseProductRepository implements ProductRepositoryInterface
{
    use BatchableTrait;
}
