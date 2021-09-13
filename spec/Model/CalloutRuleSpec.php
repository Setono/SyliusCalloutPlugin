<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Model;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRule;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;

final class CalloutRuleSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CalloutRule::class);
    }

    public function it_implements_product_callout_rule_interface(): void
    {
        $this->shouldHaveType(CalloutRuleInterface::class);
    }

    public function it_has_no_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    public function its_type_is_mutable(): void
    {
        $this->setType('has_product');
        $this->getType()->shouldReturn('has_product');
    }

    public function its_configuration_is_mutable(): void
    {
        $this->setConfiguration(['1' => '1234']);
        $this->getConfiguration()->shouldReturn(['1' => '1234']);
    }

    public function its_product_callout_is_mutable(CalloutInterface $callout): void
    {
        $this->setCallout($callout);
        $this->getCallout()->shouldReturn($callout);
    }
}
