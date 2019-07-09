<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface CalloutInterface extends
    ChannelsAwareInterface,
    ResourceInterface,
    ToggleableInterface,
    TranslatableInterface
{
    public const POSITION_TOP_LEFT = 'top_left';

    public const POSITION_TOP_RIGHT = 'top_right';

    public const POSITION_BOTTOM_RIGHT = 'bottom_right';

    public const POSITION_BOTTOM_LEFT = 'bottom_left';

    public function getName(): ?string;

    public function setName(string $name): void;

    public function getTimePeriodStart(): ?DateTimeInterface;

    public function setTimePeriodStart(?DateTimeInterface $timePeriodStart): void;

    public function getTimePeriodEnd(): ?DateTimeInterface;

    public function setTimePeriodEnd(?DateTimeInterface $timePeriodEnd): void;

    public function getPriority(): int;

    public function setPriority(int $priority): void;

    public function getPosition(): ?string;

    public function setPosition(?string $position): void;

    public function getText(): ?string;

    public function setText(?string $text): void;

    public function getCode(): ?string;

    public function setCode(string $code): void;

    /**
     * @return Collection|CalloutRuleInterface[]
     */
    public function getRules(): Collection;

    public function hasRules(): bool;

    public function hasRule(CalloutRuleInterface $rule): bool;

    public function addRule(CalloutRuleInterface $rule): void;

    public function removeRule(CalloutRuleInterface $rule): void;

    public static function getAllowedPositions(): array;
}
