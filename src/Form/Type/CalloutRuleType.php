<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

final class CalloutRuleType extends AbstractConfigurableCalloutRuleType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        // @todo Fix labels at next plugin version
        $builder
            ->add('type', CalloutRuleChoiceType::class, [
                'label' => 'setono_sylius_callout.ui.type',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
        ;
    }
}
