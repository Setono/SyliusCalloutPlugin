<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Model;

use Sylius\Component\Core\Model\ProductInterface as BaseProductInterface;

interface ProductInterface extends CalloutsAwareInterface, BaseProductInterface
{
}
