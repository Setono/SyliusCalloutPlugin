<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Fixture\Factory;

use Setono\SyliusCalloutPlugin\Factory\CalloutRuleFactoryInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalloutRuleExampleFactory extends AbstractExampleFactory
{
    private CalloutRuleFactoryInterface $calloutRuleFactory;

    private OptionsResolver $optionsResolver;

    public function __construct(CalloutRuleFactoryInterface $calloutRuleFactory)
    {
        $this->calloutRuleFactory = $calloutRuleFactory;

        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): CalloutRuleInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var CalloutRuleInterface $calloutRule */
        $calloutRule = $this->calloutRuleFactory->createNew();
        $calloutRule->setType($options['type']);
        $calloutRule->setConfiguration($options['configuration']);

        return $calloutRule;
    }

    /**
     * @inheritdoc
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined('type')
            ->setAllowedTypes('type', 'string')

            ->setDefined('configuration')
            ->setAllowedTypes('configuration', 'array')
        ;
    }
}
