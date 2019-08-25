<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\DoctrineORMBatcher\Batch\RangeBatchInterface;

final class AssignEligibleCalloutsToProductsBatch implements CommandInterface
{
    /** @var RangeBatchInterface */
    private $batch;

    public function __construct(RangeBatchInterface $batch)
    {
        $this->batch = $batch;
    }

    public function getBatch(): RangeBatchInterface
    {
        return $this->batch;
    }
}
