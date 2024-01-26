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
            foreach ($message->callouts as $calloutShape) {
                $callout = $this->calloutRepository->findOneByCode($calloutShape[0]);
                if (null === $callout) {
                    continue;
                }

                if (null !== $calloutShape[1] && $calloutShape[1] !== $callout->getVersion()) {
                    continue;
                }

                if (!$callout->isEnabled()) {
                    continue;
                }

                $callouts[] = $callout;
            }

            if ([] === $callouts) {
                return;
            }
        }

        $this->assign(callouts: $callouts);
    }
}
