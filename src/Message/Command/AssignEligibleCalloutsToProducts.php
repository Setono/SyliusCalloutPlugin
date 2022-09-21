<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use DateTimeInterface;

final class AssignEligibleCalloutsToProducts implements CommandInterface
{
    private DateTimeInterface $triggeredAt;

    public function __construct(DateTimeInterface $triggeredAt)
    {
        $this->triggeredAt = $triggeredAt;
    }

    public function getTriggeredAt(): DateTimeInterface
    {
        return $this->triggeredAt;
    }
}
