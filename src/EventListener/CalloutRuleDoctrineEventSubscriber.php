<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
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

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof CalloutRuleInterface) {
            return;
        }

        if ($args->hasChangedField('configuration') || $args->hasChangedField('type')) {
            $this->scheduleCalloutUpdate(
                $entity->getCallout()
            );
        }
    }
}
