<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type;

use Setono\SyliusCalloutPlugin\Form\Type\Translation\CalloutTranslationType;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'setono_sylius_callout.ui.code',
                'disabled' => null !== $builder->getData()->getCode(),
            ])
            ->add('name', TextType::class, [
                'label' => 'setono_sylius_callout.ui.name',
            ])
            ->add('startsAt', DateTimeType::class, [
                'label' => 'setono_sylius_callout.ui.starts_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('endsAt', DateTimeType::class, [
                'label' => 'setono_sylius_callout.ui.ends_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'setono_sylius_callout.ui.priority',
            ])
            ->add('position', ChoiceType::class, [
                'label' => 'setono_sylius_callout.ui.position',
                'choices' => [
                    'setono_sylius_callout.ui.top_left' => CalloutInterface::POSITION_TOP_LEFT,
                    'setono_sylius_callout.ui.top_right' => CalloutInterface::POSITION_TOP_RIGHT,
                    'setono_sylius_callout.ui.bottom_left' => CalloutInterface::POSITION_BOTTOM_LEFT,
                    'setono_sylius_callout.ui.bottom_right' => CalloutInterface::POSITION_BOTTOM_RIGHT,
                ],
                'placeholder' => 'setono_sylius_callout.ui.select_position',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'setono_sylius_callout.ui.enabled',
                'required' => false,
            ])
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'sylius.form.product.channels',
            ])
            ->add('rules', CalloutRuleCollectionType::class, [
                'label' => 'setono_sylius_callout.ui.rules',
                'required' => false,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'setono_sylius_callout.ui.text',
                'entry_type' => CalloutTranslationType::class,
                'validation_groups' => ['setono_sylius_callout'], // todo move these constraints to a validation file
                'constraints' => [new Valid()],
            ])
        ;
    }
}
