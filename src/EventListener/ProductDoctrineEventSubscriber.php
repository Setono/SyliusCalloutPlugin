<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;

final class ProductDoctrineEventSubscriber extends AbstractCalloutDoctrineEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof ProductInterface) {
            return;
        }

        // We want to reassign callouts to product on product code/mainTaxon changed
        // as only this matters for rules
        if ($args->hasChangedField('code') || $args->hasChangedField('mainTaxon')) {
            $this->scheduleProductCalloutAssign($entity);
        }
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        // We want to assign callouts to product on product creation
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof ProductInterface) {
                continue;
            }

            $this->scheduleProductCalloutAssign($entity);
        }

        /** @var PersistentCollection $collection */
        foreach ($uow->getScheduledCollectionUpdates() as $collection) {
            $owner = $collection->getOwner();
            if (!$owner instanceof ProductInterface) {
                continue;
            }

            foreach ([$collection->getDeleteDiff(), $collection->getInsertDiff()] as $diff) {
                foreach ($diff as $entity) {
                    if (!$entity instanceof ProductTaxonInterface) {
                        break;
                    }

                    $this->scheduleProductCalloutAssign($owner);
                }
            }
        }
    }
}
