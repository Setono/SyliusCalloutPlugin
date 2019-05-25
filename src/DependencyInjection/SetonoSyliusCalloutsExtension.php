<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\DependencyInjection;

use Setono\SyliusCalloutsPlugin\Message\Command\AssignProductCallouts;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusCalloutsExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_callouts.messenger.command_bus', $config['messenger']['command_bus']);
        $container->setParameter('setono_sylius_callouts.messenger.transport', $config['messenger']['transport']);

        $loader->load('services.xml');

        $this->registerResources('setono_sylius_callouts_plugin', $config['driver'], $config['resources'], $container);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $container->getExtensionConfig($this->getAlias()));

        $transport = $config['messenger']['transport'];

        if (null === $transport) {
            return;
        }

        $container->prependExtensionConfig('framework', [
            'messenger' => [
                'routing' => [
                    AssignProductCallouts::class => $transport,
                ],
            ],
        ]);
    }
}
