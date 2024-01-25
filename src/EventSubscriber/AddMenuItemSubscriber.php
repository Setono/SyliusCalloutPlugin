<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventSubscriber;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AddMenuItemSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.menu.admin.main' => 'addMenuItem',
        ];
    }

    public function addMenuItem(MenuBuilderEvent $event): void
    {
        $parent = $event->getMenu()->getChild('marketing');
        if (null === $parent) {
            return;
        }

        $parent
            ->addChild('callouts', ['route' => 'setono_sylius_callout_admin_callout_index'])
            ->setLabel('setono_sylius_callout.ui.callouts')
            ->setLabelAttribute('icon', 'bullhorn')
        ;
    }
}
