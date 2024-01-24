<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;

final class CalloutType extends AbstractResourceType
{
    /**
     * @param class-string $dataClass
     * @param list<string> $validationGroups
     */
    public function __construct(
        private readonly array $elements,
        private readonly array $positions,
        string $dataClass,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var mixed $data */
        $data = $builder->getData();

        $builder
            ->add('code', TextType::class, [
                'label' => 'setono_sylius_callout.form.callout.code',
                'disabled' => $data instanceof CalloutInterface && null !== $data->getCode(),
            ])
            ->add('name', TextType::class, [
                'label' => 'setono_sylius_callout.form.callout.name',
            ])
            ->add('startsAt', DateTimeType::class, [
                'label' => 'setono_sylius_callout.form.callout.starts_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('endsAt', DateTimeType::class, [
                'label' => 'setono_sylius_callout.form.callout.ends_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'setono_sylius_callout.form.callout.priority',
            ])
            ->add('elements', ChoiceType::class, [
                'label' => 'setono_sylius_callout.form.callout.elements',
                'multiple' => true,
                'choices' => $this->elements,
                'choice_label' => static function (string $element): string {
                    return sprintf('setono_sylius_callout.form.callout.element_labels.%s', $element);
                },
            ])
            ->add('position', ChoiceType::class, [
                'label' => 'setono_sylius_callout.form.callout.position',
                'choices' => $this->positions,
                'choice_label' => static function (string $position): string {
                    return sprintf('setono_sylius_callout.form.callout.positions.%s', $position);
                },
                'placeholder' => 'setono_sylius_callout.form.callout.select_position',
            ])
            ->add('color', TextType::class, [
                'label' => 'setono_sylius_callout.form.callout.color',
                'required' => false,
            ])
            ->add('backgroundColor', TextType::class, [
                'label' => 'setono_sylius_callout.form.callout.background_color',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'setono_sylius_callout.form.callout.enabled',
                'required' => false,
            ])
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'setono_sylius_callout.form.callout.channels',
            ])
            ->add('rules', CalloutRuleCollectionType::class, [
                'label' => 'setono_sylius_callout.form.callout.rules',
                'required' => false,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'setono_sylius_callout.form.callout.translations',
                'entry_type' => CalloutTranslationType::class,
                'validation_groups' => $this->validationGroups,
                'constraints' => [new Valid()], // todo move these constraints to a validation file
            ]);
    }
}
