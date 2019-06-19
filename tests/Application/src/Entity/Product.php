<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareTrait;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Core\Model\Product as BaseProduct;

final class Product extends BaseProduct implements ProductInterface
{
    use CalloutsAwareTrait;

    public function __construct()
    {
        parent::__construct();

        $this->callouts = new ArrayCollection();
    }
}
