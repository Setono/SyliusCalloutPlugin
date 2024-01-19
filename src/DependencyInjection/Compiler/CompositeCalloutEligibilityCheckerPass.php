<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CompositeCalloutEligibilityCheckerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $serviceIds = [
            'setono_sylius_callout.callout_eligibility_checker',
            'setono_sylius_callout.rendering_callout_eligibility_checker',
        ];

        foreach ($serviceIds as $serviceId) {
            $container->getDefinition($serviceId)->setArguments([
                array_map(
                    static function ($id): Reference {
                        return new Reference($id);
                    },
                    // Tag have same name as service id
                    array_keys($container->findTaggedServiceIds($serviceId)),
                ),
            ]);
        }
    }
}
