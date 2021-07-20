<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

class Callout implements CalloutInterface
{
    use ToggleableTrait;
    use TimestampableTrait;
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    /** @var int */
    protected $id;

    /** @var string */
    protected $code;

    /** @var string */
    protected $name;

    /** @var DateTimeInterface|null */
    protected $startsAt;

    /** @var DateTimeInterface|null */
    protected $endsAt;

    /** @var int */
    protected $priority = 0;

    /** @var string|null */
    protected $position;

    /** @var Collection|ChannelInterface[] */
    protected $channels;

    /** @var Collection|CalloutRuleInterface[] */
    protected $rules;

    /** @var DateTimeInterface|null */
    protected $rulesAssignedAt;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
        $this->rules = new ArrayCollection();
        $this->initializeTranslationsCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStartsAt(): ?DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(?DateTimeInterface $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function getEndsAt(): ?DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(?DateTimeInterface $endsAt): void
    {
        $this->endsAt = $endsAt;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function getText(): ?string
    {
        return $this->getCalloutTranslation()->getText();
    }

    public function setText(string $text): void
    {
        $this->getCalloutTranslation()->setText($text);
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(BaseChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    public function removeChannel(BaseChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    public function hasChannel(BaseChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function hasRules(): bool
    {
        return !$this->rules->isEmpty();
    }

    public function hasRule(CalloutRuleInterface $rule): bool
    {
        return $this->rules->contains($rule);
    }

    public function addRule(CalloutRuleInterface $rule): void
    {
        if (!$this->hasRule($rule)) {
            $rule->setCallout($this);
            $this->rules->add($rule);
        }
    }

    public function removeRule(CalloutRuleInterface $rule): void
    {
        // @todo Better way
        // We don't want this
        // as we need to know callout id on rule remove
        // at Doctrine Event Listener
        // $rule->setCallout(null);
        $this->rules->removeElement($rule);
    }

    public function getRulesAssignedAt(): ?DateTimeInterface
    {
        return $this->rulesAssignedAt;
    }

    public function setRulesAssignedAt(?DateTimeInterface $rulesAssignedAt): void
    {
        $this->rulesAssignedAt = $rulesAssignedAt;
    }

    public static function getAllowedPositions(): array
    {
        return [
            self::POSITION_TOP => self::POSITION_TOP,
            self::POSITION_RIGHT => self::POSITION_RIGHT,
            self::POSITION_BOTTOM => self::POSITION_BOTTOM,
            self::POSITION_LEFT => self::POSITION_LEFT,
            self::POSITION_TOP_LEFT => self::POSITION_TOP_LEFT,
            self::POSITION_TOP_RIGHT => self::POSITION_TOP_RIGHT,
            self::POSITION_BOTTOM_RIGHT => self::POSITION_BOTTOM_RIGHT,
            self::POSITION_BOTTOM_LEFT => self::POSITION_BOTTOM_LEFT,
        ];
    }

    protected function getCalloutTranslation(): CalloutTranslationInterface
    {
        /** @var CalloutTranslationInterface $translation */
        $translation = $this->getTranslation();

        return $translation;
    }

    protected function createTranslation(): CalloutTranslation
    {
        return new CalloutTranslation();
    }
}
