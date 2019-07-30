<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Assigner;

interface CalloutAssignerInterface
{
    public function assign(): void;
}
