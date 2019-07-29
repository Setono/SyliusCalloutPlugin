<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use Sylius\Component\Core\Model\ProductInterface as BaseProductInterface;

interface ProductInterface extends
    BaseProductInterface,
    CalloutsAwareInterface
{
}
