<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Assigner;

interface ProductCalloutsAssignerInterface
{
    public function assign(): void;
}
