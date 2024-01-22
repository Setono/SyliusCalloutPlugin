<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener\Doctrine;

use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Setono\SyliusCalloutPlugin\Message\Command\AssignCallout;
use Setono\SyliusCalloutPlugin\Message\Command\AssignCallouts;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class HandleCalloutRuleUpdateEventListener
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    /** @var list<CalloutInterface> */
    private array $calloutsToAssign = [];

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof CalloutRuleInterface) {
            return;
        }

        $callout = $entity->getCallout();
        if (!$callout instanceof CalloutInterface) {
            return;
        }

        $this->commandBus->dispatch(new AssignCallout($callout));
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof CalloutRuleInterface) {
            return;
        }

        if (!$args->hasChangedField('configuration') && !$args->hasChangedField('type')) {
            return;
        }

        $callout = $entity->getCallout();
        if (!$callout instanceof CalloutInterface) {
            return;
        }

        $this->calloutsToAssign[] = $callout;
    }

    public function postUpdate(): void
    {
        if ([] === $this->calloutsToAssign) {
            return;
        }

        $this->commandBus->dispatch(new AssignCallouts($this->calloutsToAssign));
    }
}
