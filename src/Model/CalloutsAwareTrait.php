<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Model;

use Doctrine\Common\Collections\Collection;

trait CalloutsAwareTrait
{
    /** @var Collection|CalloutInterface[] */
    private $callouts;

    public function getCallouts(): Collection
    {
        return $this->callouts;
    }

    public function addCallout(CalloutInterface $callout): void
    {
        if (!$this->hasCallout($callout)) {
            $this->callouts->add($callout);
        }
    }

    public function removeCallout(CalloutInterface $callout): void
    {
        if ($this->hasCallout($callout)) {
            $this->callouts->removeElement($callout);
        }
    }

    public function hasCallout(CalloutInterface $callout): bool
    {
        return $this->callouts->contains($callout);
    }

    public function setCallouts(Collection $callouts): void
    {
        $this->callouts = $callouts;
    }
}
