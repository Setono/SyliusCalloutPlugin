<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Menu;

use Knp\Menu\ItemInterface;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Menu\CalloutsMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class CalloutsMenuBuilderSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CalloutsMenuBuilder::class);
    }

    function it_adds_product_callouts_item(
        MenuBuilderEvent $event,
        ItemInterface $mainMenu,
        ItemInterface $catalogMenu,
        ItemInterface $calloutsMenu
    ): void {
        $event->getMenu()->willReturn($mainMenu);
        $mainMenu->getChild('catalog')->willReturn($catalogMenu);

        $catalogMenu
            ->addChild('callouts', ['route' => 'setono_sylius_callout_admin_callout_index'])
            ->willReturn($calloutsMenu)
        ;
        $calloutsMenu->setLabel('setono_sylius_callout.ui.callouts')->willReturn($calloutsMenu);
        $calloutsMenu->setLabelAttribute('icon', 'bullhorn')->shouldBeCalled();

        $this->addCalloutsItem($event);
    }
}
