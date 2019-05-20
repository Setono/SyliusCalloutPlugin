<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Form\Type;

use Setono\SyliusCalloutsPlugin\Form\Type\Translation\CalloutTranslationType;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;

final class CalloutType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.code',
                'disabled' => null !== $builder->getData()->getCode(),
            ])
            ->add('timePeriodStart', DateTimeType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.time_period_start',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('timePeriodEnd', DateTimeType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.time_period_end',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.priority',
            ])
            ->add('position', ChoiceType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.position',
                'choices' => [
                    'setono_sylius_callouts_plugin.ui.top_left_corner' => CalloutInterface::TOP_LEFT_CORNER_POSITION,
                    'setono_sylius_callouts_plugin.ui.top_right_corner' => CalloutInterface::TOP_RIGHT_CORNER_POSITION,
                    'setono_sylius_callouts_plugin.ui.bottom_left_corner' => CalloutInterface::BOTTOM_LEFT_CORNER_POSITION,
                    'setono_sylius_callouts_plugin.ui.bottom_right_corner' => CalloutInterface::BOTTOM_RIGHT_CORNER_POSITION,
                ],
            ])
            ->add('html', TextareaType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.html',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.enabled',
            ])
            ->add('rules', CalloutRuleCollectionType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.rules',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'setono_sylius_callouts_plugin.ui.name',
                'entry_type' => CalloutTranslationType::class,
                'validation_groups' => ['setono'],
                'constraints' => [new Valid()],
            ])
        ;
    }
}
