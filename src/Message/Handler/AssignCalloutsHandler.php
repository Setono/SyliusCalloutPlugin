<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Setono\SyliusCalloutPlugin\Message\Command\AssignCallouts;

final class AssignCalloutsHandler extends AbstractAssignCalloutsHandler
{
    public function __invoke(AssignCallouts $message): void
    {
        $callouts = [];
        if ([] !== $message->callouts) {
            $callouts = $this->calloutRepository->findEnabled($message->callouts);
        }

        $this->assign(callouts: $callouts);
    }
}
