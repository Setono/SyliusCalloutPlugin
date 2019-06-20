<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Model;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Model\CalloutTranslation;
use Setono\SyliusCalloutPlugin\Model\CalloutTranslationInterface;

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

    function its_text_is_mutable(): void
    {
        $this->setText('product callout');
        $this->getText()->shouldReturn('product callout');
    }
}
