<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class CalloutRuleCollectionType extends AbstractConfigurationCollectionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('entry_type', CalloutRuleType::class);
    }
}
