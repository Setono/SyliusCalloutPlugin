<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;

final class CalloutRuleDoctrineEventSubscriber extends AbstractCalloutDoctrineEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::postFlush,
        ];
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof CalloutRuleInterface) {
            return;
        }

        if ($args->hasChangedField('configuration') || $args->hasChangedField('type')) {
            $callout = $entity->getCallout();
            if (!$callout instanceof CalloutInterface) {
                return;
            }

            $this->scheduleCalloutUpdate($callout);
        }
    }
}
