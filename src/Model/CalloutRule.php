<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Model;

class CalloutRule implements CalloutRuleInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $type;

    /** @var CalloutInterface */
    protected $callout;

    /** @var array */
    protected $configuration = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getCallout(): ?CalloutInterface
    {
        return $this->callout;
    }

    public function setCallout(?CalloutInterface $callout): void
    {
        $this->callout = $callout;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }
}
