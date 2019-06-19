<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Assigner;

interface CalloutAssignerInterface
{
    public function assign(): void;
}
