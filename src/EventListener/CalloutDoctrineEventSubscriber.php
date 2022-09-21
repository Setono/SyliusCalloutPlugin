<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CalloutDoctrineEventSubscriber extends AbstractCalloutDoctrineEventSubscriber implements EventSubscriber
{
    /** @var bool */
    private $isNoRulesEligible;

    public function __construct(
        EntityManager $calloutManager,
        MessageBusInterface $commandBus,
        bool $manualTriggering = false,
        bool $isNoRulesEligible = false
    ) {
        parent::__construct($calloutManager, $commandBus, $manualTriggering);

        $this->isNoRulesEligible = $isNoRulesEligible;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        if ($this->isNoRulesEligible) {
            foreach ($uow->getScheduledEntityInsertions() as $entity) {
                if (!$entity instanceof CalloutInterface) {
                    continue;
                }

                $this->scheduleCalloutUpdate($entity);
            }
        }

        // Here we need only rules collection changes to track as far as
        // creating/updating Callout itself (without rules) not require
        // any assign triggering

        /** @var PersistentCollection $collection */
        foreach ($uow->getScheduledCollectionUpdates() as $collection) {
            if (!$collection->getOwner() instanceof CalloutInterface) {
                continue;
            }

            foreach ([$collection->getDeleteDiff(), $collection->getInsertDiff()] as $diff) {
                foreach ($diff as $entity) {
                    if (!$entity instanceof CalloutRuleInterface) {
                        break;
                    }

                    $callout = $entity->getCallout();
                    if (!$callout instanceof CalloutInterface) {
                        break;
                    }

                    $this->scheduleCalloutUpdate($callout);
                }
            }
        }
    }
}
