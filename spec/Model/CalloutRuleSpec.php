<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Model;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutRule;
use Setono\SyliusCalloutsPlugin\Model\CalloutRuleInterface;

final class CalloutRuleSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CalloutRule::class);
    }

    function it_implements_product_callout_rule_interface(): void
    {
        $this->shouldHaveType(CalloutRuleInterface::class);
    }

    function it_has_no_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function its_type_is_mutable(): void
    {
        $this->setType('has_product');
        $this->getType()->shouldReturn('has_product');
    }

    function its_configuration_is_mutable(): void
    {
        $this->setConfiguration(['1' => '1234']);
        $this->getConfiguration()->shouldReturn(['1' => '1234']);
    }

    function its_product_callout_is_mutable(CalloutInterface $callout): void
    {
        $this->setCallout($callout);
        $this->getCallout()->shouldReturn($callout);
    }
}
