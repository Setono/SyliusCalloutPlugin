<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProduct;
use Setono\SyliusCalloutPlugin\Message\Command\AssignEligibleCalloutsToProducts;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractCalloutDoctrineEventSubscriber
{
    protected EntityManager $calloutManager;

    protected MessageBusInterface $commandBus;

    protected bool $manualTriggering;

    /** @var ArrayCollection|CalloutInterface[] */
    private $calloutsToUpdate;

    /** @var ArrayCollection|ProductInterface[] */
    private $productsToAssign;

    public function __construct(
        EntityManager $calloutManager,
        MessageBusInterface $commandBus,
        bool $manualTriggering = false,
    ) {
        $this->calloutManager = $calloutManager;
        $this->commandBus = $commandBus;
        $this->manualTriggering = $manualTriggering;

        $this->calloutsToUpdate = new ArrayCollection();
        $this->productsToAssign = new ArrayCollection();
    }

    protected function scheduleCalloutUpdate(CalloutInterface $callout): void
    {
        if ($this->calloutsToUpdate->contains($callout)) {
            return;
        }

        $this->calloutsToUpdate->add($callout);
    }

    protected function scheduleProductCalloutAssign(ProductInterface $product): void
    {
        if ($this->productsToAssign->contains($product)) {
            return;
        }

        $this->productsToAssign->add($product);
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        if (!$this->productsToAssign->isEmpty()) {
            foreach ($this->productsToAssign as $product) {
                $this->commandBus->dispatch(new AssignEligibleCalloutsToProduct($product));
            }
            $this->productsToAssign->clear();
        }

        if (!$this->calloutsToUpdate->isEmpty()) {
            $now = new DateTime();
            foreach ($this->calloutsToUpdate as $callout) {
                $callout->setUpdatedAt($now);
                $this->calloutManager->persist($callout);
            }
            $this->calloutsToUpdate->clear();
            $this->calloutManager->flush();

            // Do not immediately assign if manual_triggering configuration option enabled
            if (!$this->manualTriggering) {
                $this->commandBus->dispatch(new AssignEligibleCalloutsToProducts($now));
            }
        }
    }
}
