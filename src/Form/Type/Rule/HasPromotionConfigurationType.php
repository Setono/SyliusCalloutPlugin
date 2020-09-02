<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Form\Type\Rule;

use Symfony\Component\Form\AbstractType;

final class HasPromotionConfigurationType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'setono_callout_rule_on_sale_configuration';
    }
}
