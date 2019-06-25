<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\DoctrineORMBatcher\Batch\Batch;

class AssignProductCallouts
{
    /** @var Batch */
    private $batch;

    public function __construct(Batch $batch)
    {
        $this->batch = $batch;
    }

    public function getBatch(): Batch
    {
        return $this->batch;
    }
}
