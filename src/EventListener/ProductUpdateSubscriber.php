<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener;

use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProduct;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProductUpdateSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.post_create' => 'handleEvent',
            'sylius.product.post_update' => 'handleEvent',
        ];
    }

    public function handleEvent(ResourceControllerEvent $event): void
    {
        $product = $event->getSubject();
        if (!$product instanceof ProductInterface) {
            return;
        }

        $this->commandBus->dispatch(new AssignEligibleCalloutsToProduct($product));
    }
}
