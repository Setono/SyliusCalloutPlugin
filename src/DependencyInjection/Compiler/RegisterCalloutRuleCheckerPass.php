<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterCalloutRuleCheckerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('setono_sylius_callouts_plugin.registry_callout_rule_checker')
        || !$container->has('setono_sylius_callouts_plugin.form_registry.callout_rule_checker')) {
            return;
        }

        $calloutRuleCheckerRegsitry = $container->getDefinition('setono_sylius_callouts_plugin.registry_callout_rule_checker');
        $calloutRuleCheckerFormTypeRegsitry = $container->getDefinition('setono_sylius_callouts_plugin.form_registry.callout_rule_checker');

        $calloutRuleCheckerTypeToLabelMap = [];
        foreach ($container->findTaggedServiceIds('setono_sylius_callouts_plugin.callout_rule_checker') as $id => $attributes) {
            if (!isset($attributes[0]['type'], $attributes[0]['label'], $attributes[0]['form_type'])) {
                throw new \InvalidArgumentException(sprintf('Tagged rule checker %s id needs to have `type`, `form_type`, and `label` attributes', $id));
            }

            $calloutRuleCheckerTypeToLabelMap[$attributes[0]['type']] = $attributes[0]['label'];
            $calloutRuleCheckerRegsitry->addMethodCall('register', [$attributes[0]['type'], new Reference($id)]);
            $calloutRuleCheckerFormTypeRegsitry->addMethodCall('add', [$attributes[0]['type'], 'default', $attributes[0]['form_type']]);
        }

        $container->setParameter('setono.callout_rules', $calloutRuleCheckerTypeToLabelMap);
    }
}
