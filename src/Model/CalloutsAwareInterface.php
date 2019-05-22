<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;

interface CalloutsAwareInterface extends ProductInterface
{
    /**
     * @return Collection|CalloutInterface[]
     */
    public function getCallouts(): Collection;

    public function hasCallout(CalloutInterface $callout): bool;

    public function addCallout(CalloutInterface $callout): void;

    public function removeCallout(CalloutInterface $callout): void;

    public function setCallouts(Collection $callouts): void;
}
