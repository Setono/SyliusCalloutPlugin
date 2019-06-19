<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class CalloutsMenuBuilder
{
    public function addCalloutsItem(MenuBuilderEvent $event): void
    {
        /** @var ItemInterface $catalogMenu */
        $catalogMenu = $event->getMenu()->getChild('catalog');

        $catalogMenu
            ->addChild('callouts', ['route' => 'setono_sylius_callout_admin_callout_index'])
            ->setLabel('setono_sylius_callout.ui.callouts')
            ->setLabelAttribute('icon', 'bullhorn')
        ;
    }
}
