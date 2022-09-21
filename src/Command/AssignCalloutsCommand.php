<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Command;

use Setono\SyliusCalloutPlugin\Callout\Assigner\CalloutAssignerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AssignCalloutsCommand extends Command
{
    private CalloutAssignerInterface $productCalloutsAssigner;

    protected static $defaultName = 'setono:sylius-callout:assign';

    public function __construct(CalloutAssignerInterface $productCalloutsAssigner)
    {
        parent::__construct();

        $this->productCalloutsAssigner = $productCalloutsAssigner;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Trigger callouts to all products assign process')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->productCalloutsAssigner->assign();

        return 0;
    }
}
