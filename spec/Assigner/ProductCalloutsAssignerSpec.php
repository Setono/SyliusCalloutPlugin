<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Assigner;

use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Setono\SyliusCalloutPlugin\Assigner\ProductCalloutsAssigner;
use Setono\SyliusCalloutPlugin\Assigner\ProductCalloutsAssignerInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use stdClass;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProductCalloutsAssignerSpec extends ObjectBehavior
{
    public function let(ProductRepositoryInterface $productRepository, MessageBusInterface $messageBus): void
    {
        $this->beConstructedWith($productRepository, $messageBus);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductCalloutsAssigner::class);
    }

    public function it_implements_product_callout_assigner_interface(): void
    {
        $this->shouldHaveType(ProductCalloutsAssignerInterface::class);
    }

    public function it_assigns_callouts_to_products(
        ProductRepositoryInterface $productRepository,
        Pagerfanta $paginator,
        ProductInterface $firstProduct,
        ProductInterface $secondProduct,
        MessageBusInterface $messageBus
    ): void {
        $productRepository->createPaginator(['enabled' => true])->willReturn($paginator);
        $paginator->getCurrentPageResults()->willReturn([$firstProduct, $secondProduct]);
        $firstProduct->getId()->willReturn(1);
        $secondProduct->getId()->willReturn(2);
        $paginator->getNbResults()->willReturn(300);
        $envelope = new Envelope(new stdClass(), []);

        $paginator->setMaxPerPage(100)->shouldBeCalled();
        $paginator->setCurrentPage(1)->shouldBeCalled();
        $paginator->setCurrentPage(2)->shouldBeCalled();
        $paginator->setCurrentPage(3)->shouldBeCalled();
        $messageBus->dispatch(Argument::any())->willReturn($envelope)->shouldBeCalled();

        $this->assign();
    }
}
