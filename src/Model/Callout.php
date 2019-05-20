<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

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

    /** @var \DateTime */
    protected $timePeriodStart;

    /** @var \DateTime */
    protected $timePeriodEnd;

    /** @var int */
    protected $priority;

    /** @var string */
    protected $position;

    /** @var string */
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

    public function setName(?string $name): void
    {
        $this->getCalloutTranslation()->setName($name);
    }

    public function getTimePeriodStart(): ?\DateTime
    {
        return $this->timePeriodStart;
    }

    public function setTimePeriodStart(?\DateTime $timePeriodStart): void
    {
        $this->timePeriodStart = $timePeriodStart;
    }

    public function getTimePeriodEnd(): ?\DateTime
    {
        return $this->timePeriodEnd;
    }

    public function setTimePeriodEnd(?\DateTime $timePeriodEnd): void
    {
        $this->timePeriodEnd = $timePeriodEnd;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): void
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

    public function setCode(?string $code): void
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

    /**
     * @return CalloutTranslationInterface|TranslationInterface
     */
    protected function getCalloutTranslation(): TranslationInterface
    {
        return $this->getTranslation();
    }

    protected function createTranslation(): CalloutTranslation
    {
        return new CalloutTranslation();
    }
}
