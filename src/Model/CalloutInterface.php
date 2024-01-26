<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\VersionedInterface;

interface CalloutInterface extends
    VersionedInterface,
    CodeAwareInterface,
    ChannelsAwareInterface,
    ResourceInterface,
    TimestampableInterface,
    ToggleableInterface,
    TranslatableInterface
{
    final public const DEFAULT_KEY = 'default';

    public function getId(): ?int;

    public function getName(): ?string;

    public function setName(string $name): void;

    public function getStartsAt(): ?DateTimeInterface;

    public function setStartsAt(?DateTimeInterface $startsAt): void;

    public function getEndsAt(): ?DateTimeInterface;

    public function setEndsAt(?DateTimeInterface $endsAt): void;

    public function getPriority(): int;

    public function setPriority(int $priority): void;

    /**
     * @return list<string>
     */
    public function getElements(): array;

    /**
     * @param list<string> $elements
     */
    public function setElements(array $elements): void;

    public function getPosition(): ?string;

    public function setPosition(?string $position): void;

    public function getColor(): ?string;

    public function setColor(?string $color): void;

    public function getBackgroundColor(): ?string;

    public function setBackgroundColor(?string $backgroundColor): void;

    public function getText(): ?string;

    public function setText(string $text): void;

    /**
     * @return Collection<array-key, CalloutRuleInterface>
     */
    public function getRules(): Collection;

    public function hasRules(): bool;

    /**
     * @param CalloutRuleInterface|string $rule if the rule is a string, it will be treated as a rule type
     */
    public function hasRule(CalloutRuleInterface|string $rule): bool;

    public function addRule(CalloutRuleInterface $rule): void;

    public function removeRule(CalloutRuleInterface $rule): void;
}
