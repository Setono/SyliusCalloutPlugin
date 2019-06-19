<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use DateTimeInterface;

class Callout implements CalloutInterface
{
    use ToggleableTrait;
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    public function __construct()
    {
        $this->rules = new ArrayCollection();
        $this->initializeTranslationsCollection();
    }

    /** @var int */
    protected $id;

    /** @var string */
    protected $code;

    /** @var DateTimeInterface|null */
    protected $timePeriodStart;

    /** @var DateTimeInterface|null */
    protected $timePeriodEnd;

    /** @var int */
    protected $priority = 0;

    /** @var string|null */
    protected $position;

    /** @var string|null */
    protected $html;

    /** @var Collection|CalloutRuleInterface[] */
    protected $rules;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->getCalloutTranslation()->getName();
    }

    public function setName(string $name): void
    {
        $this->getCalloutTranslation()->setName($name);
    }

    public function getTimePeriodStart(): ?DateTimeInterface
    {
        return $this->timePeriodStart;
    }

    public function setTimePeriodStart(?DateTimeInterface $timePeriodStart): void
    {
        $this->timePeriodStart = $timePeriodStart;
    }

    public function getTimePeriodEnd(): ?DateTimeInterface
    {
        return $this->timePeriodEnd;
    }

    public function setTimePeriodEnd(?DateTimeInterface $timePeriodEnd): void
    {
        $this->timePeriodEnd = $timePeriodEnd;
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

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): void
    {
        $this->html = $html;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
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
        $rule->setCallout(null);
        $this->rules->removeElement($rule);
    }

    public static function getAllowedPositions(): array
    {
        return [
            self::TOP_LEFT_CORNER_POSITION,
            self::TOP_RIGHT_CORNER_POSITION,
            self::BOTTOM_RIGHT_CORNER_POSITION,
            self::BOTTOM_LEFT_CORNER_POSITION,
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
        // todo is this the correct way to do this?
        return new CalloutTranslation();
    }
}
