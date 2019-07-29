<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Fixture;

use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class CalloutFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'setono_sylius_callout';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        /** @var NodeBuilder $node */
        $node = $resourceNode->children();
        $node->scalarNode('name');
        $node->scalarNode('code');
        $node->scalarNode('text');
        $node->variableNode('translations')->cannotBeEmpty()->defaultValue([]);
        $node->scalarNode('position')->cannotBeEmpty();
        $node->scalarNode('priority')->defaultNull();
        $node->scalarNode('starts_at')->defaultNull();
        $node->scalarNode('ends_at')->defaultNull();
        $node->arrayNode('channels')->scalarPrototype();

        $rulesNode = $node->arrayNode('rules');
        $rulesNode->defaultValue([]);
        $rulesPrototype = $rulesNode->arrayPrototype()->children();
        $rulesPrototype->scalarNode('type')->cannotBeEmpty();
        $rulesPrototype->variableNode('configuration');

        $node->booleanNode('enabled')->defaultTrue();
    }
}
