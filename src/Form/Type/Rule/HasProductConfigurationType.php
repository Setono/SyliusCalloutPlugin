<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type\Rule;

use Sylius\Bundle\AdminBundle\Form\Type\ProductAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class HasProductConfigurationType extends AbstractType
{
    public function __construct(private readonly DataTransformerInterface $productsToCodesTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('products', ProductAutocompleteType::class, [
                'label' => 'setono_sylius_callout.form.callout_rule.contains_product.products',
                'multiple' => true,
            ]);

        $builder->get('products')->addModelTransformer($this->productsToCodesTransformer);
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_callout_rule_has_product_configuration';
    }
}
