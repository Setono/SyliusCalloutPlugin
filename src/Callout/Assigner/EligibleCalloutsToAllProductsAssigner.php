<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Assigner;

use Safe\DateTime;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProducts;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Assign eligible callouts to all products
 */
final class EligibleCalloutsToAllProductsAssigner implements CalloutAssignerInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function assign(): void
    {
        $this->messageBus->dispatch(new AssignEligibleCalloutsToProducts(new DateTime()));
    }
}
