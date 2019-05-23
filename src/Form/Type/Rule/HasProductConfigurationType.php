<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Form\Type\Rule;

use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class HasProductConfigurationType extends AbstractType
{
    /** @var DataTransformerInterface */
    private $productsToCodesTransformer;

    public function __construct(DataTransformerInterface $productsToCodesTransformer)
    {
        $this->productsToCodesTransformer = $productsToCodesTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('products', ProductAutocompleteChoiceType::class, [
                'label' => 'sylius.form.promotion_filter.products',
                'multiple' => true,
            ]);

        $builder->get('products')->addModelTransformer($this->productsToCodesTransformer);
    }

    public function getBlockPrefix(): string
    {
        return 'setono_callout_rule_has_product_configuration';
    }
}
