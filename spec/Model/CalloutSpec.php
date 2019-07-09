<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Model;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;

final class CalloutSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Callout::class);
    }

    function it_implements_product_callout_interface(): void
    {
        $this->shouldHaveType(CalloutInterface::class);
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

    function its_starts_at_is_mutable(\DateTime $startsAt): void
    {
        $this->setStartsAt($startsAt);
        $this->getStartsAt()->shouldReturn($startsAt);
    }

    function its_ends_at_is_mutable(\DateTime $endsAt): void
    {
        $this->setEndsAt($endsAt);
        $this->getEndsAt()->shouldReturn($endsAt);
    }

    function its_priority_is_mutable(): void
    {
        $this->setPriority(2);
        $this->getPriority()->shouldReturn(2);
    }

    function its_position_is_mutable(): void
    {
        $this->setPosition('top_left');
        $this->getPosition()->shouldReturn('top_left');
    }

    function its_code_is_mutable(): void
    {
        $this->setCode('summer_promotion');
        $this->getCode()->shouldReturn('summer_promotion');
    }

    function it_associates_rules(CalloutRuleInterface $firstRule, CalloutRuleInterface $secondRule): void
    {
        $this->addRule($firstRule);
        $this->hasRule($firstRule)->shouldReturn(true);

        $this->hasRule($secondRule)->shouldReturn(false);

        $this->removeRule($firstRule);
        $this->hasRule($firstRule)->shouldReturn(false);
    }

    function it_toggles(): void
    {
        $this->enable();
        $this->isEnabled()->shouldReturn(true);
        $this->disable();
        $this->isEnabled()->shouldReturn(false);
    }
}
