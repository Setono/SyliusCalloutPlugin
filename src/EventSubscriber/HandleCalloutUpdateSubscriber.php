<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventSubscriber;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Setono\SyliusCalloutPlugin\Message\Command\AssignCallouts;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * This subscriber listens for changes to callouts and callout rules
 * and dispatches a message to assign the callouts to products
 */
final class HandleCalloutUpdateSubscriber implements EventSubscriberInterface
{
    /**
     * The keys are the callout codes and the values are true
     *
     * @var array<string, bool>
     */
    private array $calloutsToAssign = [];

    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'setono_sylius_callout.callout.post_create' => 'postUpdate',
            'setono_sylius_callout.callout.post_update' => 'postUpdate',
        ];
    }

    /**
     * This is called when a callout is persisted. When a callout is persisted it's obviously new, so we want to assign
     */
    public function prePersist(PrePersistEventArgs $event): void
    {
        $callout = $event->getObject();
        if (!$callout instanceof CalloutInterface) {
            return;
        }

        $this->calloutsToAssign[(string) $callout->getCode()] = true;
    }

    /**
     * This is called when a callout or callout rule is updated
     */
    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $obj = $event->getObject();

        if ($obj instanceof CalloutInterface) {
            $this->preUpdateCallout($obj, $event);
        }

        if ($obj instanceof CalloutRuleInterface) {
            $this->preUpdateCalloutRule($obj, $event);
        }
    }

    /**
     * This is called when a callout is updated. Before assigning we check if the changed fields are all irrelevant to the assignment process
     */
    private function preUpdateCallout(CalloutInterface $callout, PreUpdateEventArgs $event): void
    {
        // this is a list of fields that are irrelevant for the assignment of callouts
        // and hence we don't want to trigger the assignment if _only_ these fields are changed
        // todo define these fields using attributes on the properties in the entity
        $irrelevantFields = ['version', 'name', 'startsAt', 'endsAt', 'priority', 'elements', 'position', 'color', 'backgroundColor', 'createdAt', 'updatedAt'];

        // now we check if _all_ of the changed fields are irrelevant
        $allIrrelevant = true;

        foreach ($event->getEntityChangeSet() as $field => $change) {
            if (!in_array($field, $irrelevantFields, true)) {
                $allIrrelevant = false;

                break;
            }
        }

        if ($allIrrelevant) {
            return;
        }

        $this->calloutsToAssign[(string) $callout->getCode()] = true;
    }

    /**
     * This is called when a callout rule is updated. Before assigning we check if either the type or configuration has changed
     */
    private function preUpdateCalloutRule(CalloutRuleInterface $calloutRule, PreUpdateEventArgs $event): void
    {
        if (!$event->hasChangedField('configuration') && !$event->hasChangedField('type')) {
            return;
        }

        $callout = $calloutRule->getCallout();
        if (null === $callout) {
            return;
        }

        $this->calloutsToAssign[(string) $callout->getCode()] = true;
    }

    /**
     * This is called when a callout is created or updated by Sylius.
     * In the lifecycle this is the last method called and therefore we can safely dispatch the message here if needed
     */
    public function postUpdate(): void
    {
        if ([] === $this->calloutsToAssign) {
            return;
        }

        $this->commandBus->dispatch(new AssignCallouts(array_keys($this->calloutsToAssign)));
    }
}
