<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;

final class CalloutDoctrineEventSubscriber extends AbstractCalloutDoctrineEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

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

                    $this->scheduleCalloutUpdate(
                        $entity->getCallout()
                    );
                }
            }
        }
    }
}
