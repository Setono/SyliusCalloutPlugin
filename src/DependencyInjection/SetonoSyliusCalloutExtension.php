<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusCalloutExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var array{
         *     elements: list<string>,
         *     positions: list<string>,
         *     assignment: array{delay: int},
         *     resources: array
         * } $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $loader->load('services.xml');

        $container->setParameter('setono_sylius_callout.elements', $config['elements']);
        $container->setParameter('setono_sylius_callout.positions', $config['positions']);
        $container->setParameter('setono_sylius_callout.assignment.delay', $config['assignment']['delay']);

        $this->registerResources('setono_sylius_callout', SyliusResourceBundle::DRIVER_DOCTRINE_ORM, $config['resources'], $container);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('framework', [
            'messenger' => [
                'buses' => [
                    'setono_sylius_callout.command_bus' => null,
                ],
            ],
        ]);

        $container->prependExtensionConfig('sylius_grid', [
            'grids' => [
                'setono_sylius_callout_admin_callout' => [
                    'driver' => [
                        'options' => [
                            'class' => '%setono_sylius_callout.model.callout.class%',
                        ],
                    ],
                    'sorting' => [
                        'priority' => 'asc',
                    ],
                    'limits' => [100, 250, 500, 1000],
                    'fields' => [
                        'code' => [
                            'type' => 'string',
                            'label' => 'setono_sylius_callout.ui.code',
                            'sortable' => null,
                        ],
                        'name' => [
                            'type' => 'string',
                            'label' => 'setono_sylius_callout.ui.name',
                            'sortable' => null,
                        ],
                        'enabled' => [
                            'type' => 'twig',
                            'label' => 'setono_sylius_callout.ui.enabled',
                            'sortable' => null,
                            'options' => [
                                'template' => '@SyliusUi/grid/field/enabled.html.twig',
                            ],
                        ],
                        'priority' => [
                            'type' => 'twig',
                            'label' => 'setono_sylius_callout.ui.priority',
                            'sortable' => null,
                            'options' => [
                                'template' => '@SyliusUi/grid/field/position.html.twig',
                            ],
                        ],
                    ],
                    'filters' => [
                        'name' => [
                            'type' => 'string',
                            'label' => 'setono_sylius_callout.ui.name',
                        ],
                        'date' => [
                            'type' => 'date',
                            'label' => 'sylius.ui.date',
                            'options' => [
                                'field' => 'endsAt',
                                'inclusive_to' => true,
                            ],
                        ],
                    ],
                    'actions' => [
                        'main' => [
                            'create' => [
                                'type' => 'create',
                            ],
                        ],
                        'item' => [
                            'update' => [
                                'type' => 'update',
                            ],
                            'delete' => [
                                'type' => 'delete',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        foreach (['create', 'update'] as $action) {
            $form = [
                'component' => 'setono_sylius_callout:callout:form',
                'props' => [
                    'resource' => '@=_context.resource',
                    'form' => '@=_context.form',
                    'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form.html.twig',
                ],
                'priority' => 0,
            ];

            if ('update' === $action) {
                $form['configuration']['method'] = 'PUT';
            }

            $container->prependExtensionConfig('sylius_twig_hooks', [
                'hooks' => [
                    sprintf('setono_sylius_callout.callout.%s.content', $action) => [
                        'form' => $form,
                    ],
                    sprintf('setono_sylius_callout.callout.%s.content.form', $action) => [
                        'sections' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections.html.twig',
                            'priority' => 0,
                        ],
                    ],
                    sprintf('setono_sylius_callout.callout.%s.content.form.sections', $action) => [
                        'notice' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/notice.html.twig',
                            'priority' => 500,
                        ],
                        'general' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/general.html.twig',
                            'priority' => 400,
                        ],
                        'configuration' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/configuration.html.twig',
                            'priority' => 300,
                        ],
                        'theming' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/theming.html.twig',
                            'priority' => 200,
                        ],
                        'rules' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/rules.html.twig',
                            'priority' => 100,
                        ],
                        'translations' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/translations.html.twig',
                            'priority' => 0,
                        ],
                    ],
                    sprintf('setono_sylius_callout.callout.%s.content.form.sections.general', $action) => [
                        'default' => [
                            'enabled' => false,
                        ],
                        'code' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/general/code.html.twig',
                            'priority' => 400,
                        ],
                        'name' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/general/name.html.twig',
                            'priority' => 300,
                        ],
                        'channels' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/general/channels.html.twig',
                            'priority' => 200,
                        ],
                        'enabled' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/general/enabled.html.twig',
                            'priority' => 100,
                        ],
                    ],
                    sprintf('setono_sylius_callout.callout.%s.content.form.sections.configuration#left', $action) => [
                        'starts_at' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/configuration/starts_at.html.twig',
                            'priority' => 100,
                        ],
                        'priority' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/configuration/priority.html.twig',
                            'priority' => 0,
                        ],
                    ],
                    sprintf('setono_sylius_callout.callout.%s.content.form.sections.configuration#right', $action) => [
                        'ends_at' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/configuration/ends_at.html.twig',
                        ],
                    ],
                    sprintf('setono_sylius_callout.callout.%s.content.form.sections.theming', $action) => [
                        'color' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/theming/color.html.twig',
                            'priority' => 400,
                        ],
                        'background_color' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/theming/background_color.html.twig',
                            'priority' => 300,
                        ],
                        'position' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/theming/position.html.twig',
                            'priority' => 200,
                        ],
                        'elements' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/theming/elements.html.twig',
                            'priority' => 100,
                        ],
                    ],
                    sprintf('setono_sylius_callout.callout.%s.content.form.sections.rules', $action) => [
                        'rules' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/rules/rules.html.twig',
                        ],
                    ],
                    sprintf('setono_sylius_callout.callout.%s.content.form.sections.translations', $action) => [
                        'label' => [
                            'template' => '@SetonoSyliusCalloutPlugin/admin/callout/form/sections/translations/text.html.twig',
                        ],
                    ],
                ],
            ]);
        }
    }
}
