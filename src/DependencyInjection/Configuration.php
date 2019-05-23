<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\DependencyInjection;

use Setono\SyliusCalloutsPlugin\Form\Type\CalloutType;
use Setono\SyliusCalloutsPlugin\Model\Callout;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutRule;
use Setono\SyliusCalloutsPlugin\Model\CalloutRuleInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutTranslation;
use Setono\SyliusCalloutsPlugin\Model\CalloutTranslationInterface;
use Setono\SyliusCalloutsPlugin\Repository\CalloutRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('setono_sylius_callouts_plugin');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
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
                                        ->scalarNode('interface')->defaultValue(CalloutInterface::class)->cannotBeEmpty()->end()
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
                                                ->scalarNode('interface')->defaultValue(CalloutTranslationInterface::class)->cannotBeEmpty()->end()
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
                                        ->scalarNode('interface')->defaultValue(CalloutRuleInterface::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
