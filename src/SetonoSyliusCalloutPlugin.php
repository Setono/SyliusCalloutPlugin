<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin;

use Setono\CompositeCompilerPass\CompositeCompilerPass;
use Setono\SyliusCalloutPlugin\DependencyInjection\Compiler\RegisterCalloutRuleCheckerPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SetonoSyliusCalloutPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CompositeCompilerPass(
            'setono_sylius_callout.checker.eligibility.composite',
            'setono_sylius_callout.callout_eligibility_checker',
        ));

        $container->addCompilerPass(new CompositeCompilerPass(
            'setono_sylius_callout.checker.rendering_eligibility.composite',
            'setono_sylius_callout.callout_rendering_eligibility_checker',
        ));

        $container->addCompilerPass(new RegisterCalloutRuleCheckerPass());
    }

    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }
}
