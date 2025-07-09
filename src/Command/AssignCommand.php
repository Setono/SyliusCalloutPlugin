<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Command;

use Setono\SyliusCalloutPlugin\Message\Command\AssignCallouts;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'setono:sylius-callout:assign',
    description: 'Trigger the assignment of pre-qualified callouts to products',
)]
final class AssignCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(new AssignCallouts());

        return 0;
    }
}
