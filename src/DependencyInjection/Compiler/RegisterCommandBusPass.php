<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

final class RegisterCommandBusPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('setono_sylius_callout.messenger.command_bus')) {
            return;
        }

        $commandBusId = $container->getParameter('setono_sylius_callout.messenger.command_bus');

        if (!$container->has($commandBusId)) {
            throw new ServiceNotFoundException($commandBusId);
        }

        $container->setAlias('setono_sylius_callout.command_bus', $commandBusId);
    }
}
