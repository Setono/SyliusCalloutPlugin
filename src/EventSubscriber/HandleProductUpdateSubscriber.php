<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventSubscriber;

use Setono\SyliusCalloutPlugin\Message\Command\AssignCalloutsToProduct;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class HandleProductUpdateSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.post_create' => 'handleProductUpdate',
            'sylius.product.post_update' => 'handleProductUpdate',
        ];
    }

    public function handleProductUpdate(ResourceControllerEvent $event): void
    {
        $product = $event->getSubject();
        if (!$product instanceof ProductInterface) {
            return;
        }

        $this->commandBus->dispatch(new AssignCalloutsToProduct($product));
    }
}
