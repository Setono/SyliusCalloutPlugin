<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type\Rule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

final class IsOnSaleConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('isOnSale', CheckboxType::class, [
            'label' => 'setono_sylius_callout.ui.is_on_sale',
        ]);
    }
}
