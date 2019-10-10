<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusCalloutExtension extends AbstractResourceExtension
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');

        $container->setParameter('setono_sylius_callout.manual_triggering', $config['manual_triggering']);
        $container->setParameter('setono_sylius_callout.no_rules_eligible', $config['no_rules_eligible']);
        $this->registerResources('setono_sylius_callout', $config['driver'], $config['resources'], $container);
    }
}
