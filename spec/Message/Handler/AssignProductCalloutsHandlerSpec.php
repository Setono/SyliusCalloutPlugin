<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Setono\DoctrineORMBatcher\Batch\Batch;
use Setono\SyliusCalloutPlugin\Message\Command\AssignProductCallouts;
use Setono\SyliusCalloutPlugin\Message\Handler\AssignProductCalloutsHandler;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Provider\CalloutProviderInterface;
use Setono\SyliusCalloutPlugin\Repository\ProductRepositoryInterface;

final class AssignProductCalloutsHandlerSpec extends ObjectBehavior
{
    public function let(
        CalloutProviderInterface $calloutProvider,
        EntityManagerInterface $productManager,
        ProductRepositoryInterface $productRepository
    ): void {
        $this->beConstructedWith($calloutProvider, $productManager, $productRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(AssignProductCalloutsHandler::class);
    }

    public function it_executes(
        CalloutProviderInterface $calloutProvider,
        EntityManagerInterface $productManager,
        ProductRepositoryInterface $productRepository,
        ProductInterface $product1,
        ProductInterface $product2,
        CalloutInterface $callout
    ): void {
        $productRepository->getBatch(Argument::cetera())->willReturn([$product1, $product2]);

        $calloutProvider->getCallouts(Argument::any())->willReturn([$callout]);

        $product1->setCallouts([$callout])->shouldBeCalled();
        $product2->setCallouts([$callout])->shouldBeCalled();

        $productManager->flush()->shouldBeCalled();

        $this->__invoke(new AssignProductCallouts(new Batch(0, 1)));
    }
}
