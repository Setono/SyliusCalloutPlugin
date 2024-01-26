<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\DependencyInjection;

use Setono\SyliusCalloutPlugin\Form\Type\CalloutType;
use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutRule;
use Setono\SyliusCalloutPlugin\Model\CalloutTranslation;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_sylius_callout');
        $rootNode = $treeBuilder->getRootNode();

        /** @psalm-suppress UndefinedInterfaceMethod,PossiblyNullReference,MixedMethodCall */
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('elements')
                    ->info('Define the elements relevant for your layout')
                    ->defaultValue(['default'])
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('positions')
                    ->info('Define the positions relevant for your layout')
                    ->defaultValue([
                        'top',
                        'right',
                        'middle',
                        'bottom',
                        'left',

                        'top_left',
                        'top_center',
                        'top_right',
                        'middle_left',
                        'middle_center',
                        'middle_right',
                        'bottom_left',
                        'bottom_center',
                        'bottom_right',
                    ])
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('assignment')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('delay')
                            ->info('The delay in seconds when assigning a callout. This is to avoid assigning the same callout multiple times within a short interval')
                            ->defaultValue(60)
                            ->min(0)

        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        /** @psalm-suppress UndefinedInterfaceMethod,PossiblyNullReference,MixedMethodCall */
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('callout')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Callout::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('repository')->defaultValue(CalloutRepository::class)->end()
                                        ->scalarNode('form')->defaultValue(CalloutType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('translation')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('options')->end()
                                        ->arrayNode('classes')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('model')->defaultValue(CalloutTranslation::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('callout_rule')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(CalloutRule::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
        ;
    }
}
