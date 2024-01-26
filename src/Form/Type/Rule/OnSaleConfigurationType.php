<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type\Rule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

final class OnSaleConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('singleVariantEligible', CheckboxType::class, [
                'label' => 'setono_sylius_callout.form.callout_rule.on_sale.single_variant_eligible',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_callout_rule_on_sale_configuration';
    }
}
