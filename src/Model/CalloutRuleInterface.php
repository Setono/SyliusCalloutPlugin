<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface CalloutRuleInterface extends ResourceInterface
{
    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getCallout(): ?CalloutInterface;

    public function setCallout(?CalloutInterface $callout): void;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;
}
