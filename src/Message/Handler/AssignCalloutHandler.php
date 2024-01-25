<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Setono\SyliusCalloutPlugin\Message\Command\AssignCallout;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

final class AssignCalloutHandler extends AbstractAssignCalloutsHandler
{
    public function __invoke(AssignCallout $message): void
    {
        $callout = $this->calloutRepository->findOneByCode($message->callout);
        if (null === $callout) {
            throw new UnrecoverableMessageHandlingException(sprintf('Callout with code "%s" does not exist', $message->callout));
        }

        if (null !== $message->version && $message->version !== $callout->getVersion()) {
            // this means the callout has been updated since the message was sent
            throw new UnrecoverableMessageHandlingException(sprintf('Callout with id %s has version %s, but version %s was expected', $message->callout, (string) $callout->getVersion(), $message->version));
        }

        if (!$callout->isEnabled()) {
            throw new UnrecoverableMessageHandlingException(sprintf('Callout with id %s is not enabled', $message->callout));
        }

        $callouts = [$callout];

        $this->assign(callouts: $callouts);
    }
}
