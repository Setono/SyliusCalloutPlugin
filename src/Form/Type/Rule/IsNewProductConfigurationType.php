<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type\Rule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class IsNewProductConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('days', IntegerType::class, [
                'label' => 'setono_sylius_callout.form.callout_rule.is_new.days',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'setono_callout_rule_has_product_configuration';
    }
}
