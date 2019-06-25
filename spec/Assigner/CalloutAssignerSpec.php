<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Assigner;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Setono\DoctrineORMBatcher\Batch\Batch;
use Setono\DoctrineORMBatcher\Batcher\IdBatcherInterface;
use Setono\SyliusCalloutPlugin\Assigner\CalloutAssigner;
use Setono\SyliusCalloutPlugin\Assigner\CalloutAssignerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class CalloutAssignerSpec extends ObjectBehavior
{
    public function let(IdBatcherInterface $batcher, MessageBusInterface $messageBus): void
    {
        $batcher->getBatches()->willReturn([new Batch(1, 100), new Batch(101, 200)]);
        $this->beConstructedWith($batcher, $messageBus);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CalloutAssigner::class);
    }

    public function it_implements_product_callout_assigner_interface(): void
    {
        $this->shouldHaveType(CalloutAssignerInterface::class);
    }

    public function it_assigns_callouts_to_products(MessageBusInterface $messageBus): void
    {
        $messageBus
            ->dispatch(Argument::any())
            ->willReturn(new Envelope(new \stdClass()))
            ->shouldBeCalledTimes(2);

        $this->assign();
    }
}
