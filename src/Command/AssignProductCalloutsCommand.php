<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Command;

use Setono\SyliusCalloutPlugin\Assigner\ProductCalloutsAssignerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AssignProductCalloutsCommand extends Command
{
    /** @var ProductCalloutsAssignerInterface */
    private $productCalloutsAssigner;

    protected static $defaultName = 'setono:sylius-callouts:assign';

    public function __construct(ProductCalloutsAssignerInterface $productCalloutsAssigner)
    {
        parent::__construct();

        $this->productCalloutsAssigner = $productCalloutsAssigner;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Assigns callouts to products')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->productCalloutsAssigner->assign();

        return 0;
    }
}
