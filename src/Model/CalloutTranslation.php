<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use Sylius\Component\Resource\Model\AbstractTranslation;

class CalloutTranslation extends AbstractTranslation implements CalloutTranslationInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $text;

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
