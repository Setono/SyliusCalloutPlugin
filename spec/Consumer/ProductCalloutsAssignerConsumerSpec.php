<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Consumer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Consumer\ProductCalloutsAssignerConsumer;
use Setono\SyliusCalloutsPlugin\Consumer\ProductCalloutsAssignerConsumerInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutsPlugin\Provider\CalloutProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductCalloutsAssignerConsumerSpec extends ObjectBehavior
{
    function let(
        CalloutProviderInterface $calloutProvider,
        RepositoryInterface $productRepository,
        EntityManagerInterface $productManager
    ): void {
        $this->beConstructedWith($calloutProvider, $productRepository, $productManager);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductCalloutsAssignerConsumer::class);
    }

    function it_implements_assigner_consumer_interface(): void
    {
        $this->shouldHaveType(ProductCalloutsAssignerConsumerInterface::class);
    }

    function it_executes(
        AMQPMessage $message,
        RepositoryInterface $productRepository,
        CalloutsAwareInterface $firstProduct,
        CalloutsAwareInterface $secondProduct,
        Collection $callouts,
        CalloutProviderInterface $calloutProvider,
        CalloutInterface $callout,
        EntityManagerInterface $productManager
    ): void {
        $message->getBody()->willReturn(serialize(['products' => [1, 2]]));
        $productRepository->findBy(['id' => [1, 2]])->willReturn([$firstProduct, $secondProduct]);

        $calloutProvider->getCallouts($firstProduct)->willReturn([$callout]);
        $calloutProvider->getCallouts($secondProduct)->willReturn([$callout]);

        $firstProduct->getCallouts()->willReturn($callouts);
        $secondProduct->getCallouts()->willReturn($callouts);

        $callouts->clear()->shouldBeCalled();
        $firstProduct->setCallouts(new ArrayCollection([$callout->getWrappedObject()]))->shouldBeCalled();
        $secondProduct->setCallouts(new ArrayCollection([$callout->getWrappedObject()]))->shouldBeCalled();
        $productManager->flush()->shouldBeCalled();

        $this->execute($message);
    }
}
