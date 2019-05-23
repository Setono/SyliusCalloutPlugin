<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Twig\Extension;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutsPlugin\Twig\Extension\RenderCalloutExtension;

final class RenderCalloutExtensionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(RenderCalloutExtension::class);
    }

    function it_is_a_twig_extension(): void
    {
        $this->shouldHaveType(\Twig_Extension::class);
    }

    function it_returns_callouts_functions(): void
    {
        $this->getFunctions()->shouldHaveCount(1);
        $this->getFunctions()[0]->getName()->shouldBeEqualTo('setono_render_callouts');
    }

    function itRendersCallouts(
        CalloutInterface $firstCallout,
        CalloutInterface $secondCallout,
        CalloutsAwareInterface $product
    ): void {
        $firstCallout->getPosition()->willReturn('top_left_corner');
        $firstCallout->getHtml()->willReturn('<h3>Hello</h3>');
        $secondCallout->getPosition()->willReturn('top_left_corner');
        $secondCallout->getHtml()->willReturn('<p>World!</p>');

        $product->getCallouts()->willReturn(new ArrayCollection([
            $firstCallout->getWrappedObject(),
            $secondCallout->getWrappedObject(),
        ]));

        $this->renderCallouts($product, 'top_left_corner')->shouldReturn('<h3>Hello</h3><p>World!</p>');
    }
}
