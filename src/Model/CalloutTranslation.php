<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use Sylius\Component\Resource\Model\AbstractTranslation;

class CalloutTranslation extends AbstractTranslation implements CalloutTranslationInterface
{
    protected ?int $id = null;

    protected ?string $text = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }
}
