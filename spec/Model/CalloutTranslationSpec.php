<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Model;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Model\CalloutTranslation;
use Setono\SyliusCalloutsPlugin\Model\CalloutTranslationInterface;

final class CalloutTranslationSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CalloutTranslation::class);
    }

    function it_implements_product_callout_translation_interface(): void
    {
        $this->shouldHaveType(CalloutTranslationInterface::class);
    }

    function it_has_no_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function its_name_is_mutable(): void
    {
        $this->setName('product callout');
        $this->getName()->shouldReturn('product callout');
    }
}
