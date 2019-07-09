<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\DoctrineORMBatcher\Batch\RangeBatch;

final class AssignProductCallouts implements CommandInterface
{
    /** @var RangeBatch */
    private $batch;

    public function __construct(RangeBatch $batch)
    {
        $this->batch = $batch;
    }

    public function getBatch(): RangeBatch
    {
        return $this->batch;
    }
}
