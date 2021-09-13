<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Callout;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Callout\CssClassBuilderInterface;
use Setono\SyliusCalloutPlugin\Callout\SemanticUiCssClassBuilder;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

class SemanticUiCssClassBuilderSpec extends ObjectBehavior
{
    public function it_implements_css_class_builder_interface(): void
    {
        $this->shouldImplement(CssClassBuilderInterface::class);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(SemanticUiCssClassBuilder::class);
    }

    public function it_returns_correct_class_attribute(CalloutInterface $callout): void
    {
        $callout->getPosition()->willReturn(CalloutInterface::POSITION_BOTTOM_LEFT);

        $this->buildClasses($callout)->shouldReturn('attached label bottom left');
    }
}
