<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareTrait;
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
