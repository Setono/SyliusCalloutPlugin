<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type;

use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

abstract class AbstractConfigurableCalloutRuleType extends AbstractResourceType
{
    /**
     * @param class-string $dataClass
     * @param list<string> $validationGroups
     */
    public function __construct(
        string $dataClass,
        private readonly FormTypeRegistryInterface $formTypeRegistry,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $type = $this->getRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }

                $this->addConfigurationFields($event->getForm(), (string) $this->formTypeRegistry->get($type, 'default'));
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
                $type = $this->getRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }

                $event->getForm()->get('type')->setData($type);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                /** @var mixed $data */
                $data = $event->getData();
                Assert::isArray($data);

                if (!isset($data['type'])) {
                    return;
                }

                Assert::string($data['type']);

                $this->addConfigurationFields($event->getForm(), (string) $this->formTypeRegistry->get($data['type'], 'default'));
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('configuration_type', null)
            ->setAllowedTypes('configuration_type', ['string', 'null'])
        ;
    }

    protected function addConfigurationFields(FormInterface $form, string $configurationType): void
    {
        $form->add('configuration', $configurationType, [
            'label' => false,
        ]);
    }

    protected function getRegistryIdentifier(FormInterface $form, mixed $data = null): ?string
    {
        if ($data instanceof CalloutRuleInterface && null !== $data->getType()) {
            return $data->getType();
        }

        /** @var mixed $identifier */
        $identifier = $form->getConfig()->getOption('configuration_type');
        Assert::nullOrString($identifier);

        return $identifier;
    }
}
