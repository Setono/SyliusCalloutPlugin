<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener;

use Setono\SyliusCalloutPlugin\Message\Command\AssignCalloutToProducts;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CalloutUpdateSubscriber implements EventSubscriberInterface
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
            'setono_sylius_callout.callout.post_create' => 'handleEvent',
            'setono_sylius_callout.callout.post_update' => 'handleEvent',
        ];
    }

    public function handleEvent(ResourceControllerEvent $event): void
    {
        $callout = $event->getSubject();
        if (!$callout instanceof CalloutInterface) {
            return;
        }

        $this->commandBus->dispatch(new AssignCalloutToProducts($callout));
    }
}
