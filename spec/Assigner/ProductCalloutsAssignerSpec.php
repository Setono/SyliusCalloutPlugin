<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Assigner;

use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Setono\SyliusCalloutsPlugin\Assigner\ProductCalloutsAssigner;
use Setono\SyliusCalloutsPlugin\Assigner\ProductCalloutsAssignerInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use stdClass;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class ProductCalloutsAssignerSpec extends ObjectBehavior
{
    function let(ProductRepositoryInterface $productRepository, MessageBusInterface $messageBus): void
    {
        $this->beConstructedWith($productRepository, $messageBus);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductCalloutsAssigner::class);
    }

    function it_implements_product_callout_assigner_interface(): void
    {
        $this->shouldHaveType(ProductCalloutsAssignerInterface::class);
    }

    function it_assigns_callouts_to_products(
        ProductRepositoryInterface $productRepository,
        Pagerfanta $paginator,
        CalloutsAwareInterface $firstProduct,
        CalloutsAwareInterface $secondProduct,
        MessageBusInterface $messageBus,
        StampInterface $stamp
    ): void {
        $productRepository->createPaginator(['enabled' => true])->willReturn($paginator);
        $paginator->getCurrentPageResults()->willReturn([$firstProduct, $secondProduct]);
        $firstProduct->getId()->willReturn(1);
        $secondProduct->getId()->willReturn(2);
        $paginator->getNbResults()->willReturn(300);
        $envelope = new Envelope(new stdClass(), $stamp->getWrappedObject());

        $paginator->setMaxPerPage(100)->shouldBeCalled();
        $paginator->setCurrentPage(1)->shouldBeCalled();
        $paginator->setCurrentPage(2)->shouldBeCalled();
        $paginator->setCurrentPage(3)->shouldBeCalled();
        $messageBus->dispatch(Argument::any())->willReturn($envelope)->shouldBeCalled();

        $this->assign();
    }
}
