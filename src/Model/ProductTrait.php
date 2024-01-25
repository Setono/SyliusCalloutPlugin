<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use Doctrine\ORM\Mapping as ORM;

trait ProductTrait
{
    /** @ORM\Column(type="json", nullable=true) */
    protected ?array $preQualifiedCallouts = null;

    public function getPreQualifiedCallouts(): array
    {
        return $this->preQualifiedCallouts ?? [];
    }

    public function addPreQualifiedCallout(CalloutInterface|string $preQualifiedCallout): void
    {
        if (null === $this->preQualifiedCallouts) {
            $this->preQualifiedCallouts = [];
        }

        if ($preQualifiedCallout instanceof CalloutInterface) {
            $preQualifiedCallout = (string) $preQualifiedCallout->getCode();
        }

        if (!in_array($preQualifiedCallout, $this->preQualifiedCallouts, true)) {
            $this->preQualifiedCallouts[] = $preQualifiedCallout;
        }
    }

    public function removePreQualifiedCallout(CalloutInterface|string $preQualifiedCallout): void
    {
        if (null === $this->preQualifiedCallouts) {
            return;
        }

        if ($preQualifiedCallout instanceof CalloutInterface) {
            $preQualifiedCallout = (string) $preQualifiedCallout->getCode();
        }

        $key = array_search($preQualifiedCallout, $this->preQualifiedCallouts, true);
        if (false !== $key) {
            unset($this->preQualifiedCallouts[$key]);
        }
    }

    public function resetPreQualifiedCallouts(): void
    {
        $this->preQualifiedCallouts = null;
    }
}
