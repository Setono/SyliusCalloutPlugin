<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface CalloutTranslationInterface extends ResourceInterface, TranslationInterface
{
    public function getText(): ?string;

    public function setText(string $text): void;
}
