<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Assigner;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Assigner\ProductCalloutsAssigner;
use Setono\SyliusCalloutsPlugin\Assigner\ProductCalloutsAssignerInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

final class ProductCalloutsAssignerSpec extends ObjectBehavior
{
    function let(ProductRepositoryInterface $productRepository, ProducerInterface $producer): void
    {
        $this->beConstructedWith($productRepository, $producer);
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
        ProducerInterface $producer
    ): void {
        $productRepository->createPaginator(['enabled' => true])->willReturn($paginator);
        $paginator->getCurrentPageResults()->willReturn([$firstProduct, $secondProduct]);
        $firstProduct->getId()->willReturn(1);
        $secondProduct->getId()->willReturn(2);
        $paginator->getNbResults()->willReturn(300);

        $paginator->setMaxPerPage(100)->shouldBeCalled();
        $paginator->setCurrentPage(1)->shouldBeCalled();
        $paginator->setCurrentPage(2)->shouldBeCalled();
        $paginator->setCurrentPage(3)->shouldBeCalled();
        $producer->publish(serialize(['products' => [1, 2]]))->shouldBeCalled();

        $this->assign();
    }
}
