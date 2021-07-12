<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Fixture\Factory;

use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Safe\DateTime;
use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalloutExampleFactory extends AbstractExampleFactory
{
    /** @var FactoryInterface */
    protected $calloutFactory;

    /** @var ObjectManager */
    protected $calloutManager;

    /** @var ExampleFactoryInterface */
    protected $calloutRuleExampleFactory;

    /** @var ChannelRepositoryInterface */
    protected $channelRepository;

    /** @var RepositoryInterface */
    protected $localeRepository;

    /** @var \Faker\Generator */
    protected $faker;

    /** @var OptionsResolver */
    protected $optionsResolver;

    public function __construct(
        FactoryInterface $calloutFactory,
        ObjectManager $calloutManager,
        ExampleFactoryInterface $calloutRuleExampleFactory,
        ChannelRepositoryInterface $channelRepository,
        RepositoryInterface $localeRepository
    ) {
        $this->calloutFactory = $calloutFactory;
        $this->calloutManager = $calloutManager;
        $this->calloutRuleExampleFactory = $calloutRuleExampleFactory;
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;

        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): CalloutInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var CalloutInterface $callout */
        $callout = $this->calloutFactory->createNew();

        $callout->setName($options['name']);
        $callout->setCode($options['code']);

        // add translation for each defined locales
        foreach ($this->getLocales() as $localeCode) {
            $this->createTranslation($callout, $localeCode, $options);
        }

        // create or replace with custom translations
        foreach ($options['translations'] as $localeCode => $translationOptions) {
            if (!is_array($translationOptions)) {
                $translationOptions = [
                    'text' => $translationOptions,
                ];
            }

            $this->createTranslation($callout, $localeCode, $translationOptions);
        }

        $callout->setPosition($options['position']);

        if (isset($options['priority'])) {
            $callout->setPriority($options['priority']);
        }

        if (isset($options['starts_at'])) {
            $callout->setStartsAt(new DateTime($options['starts_at']));
        }

        if (isset($options['ends_at'])) {
            $callout->setEndsAt(new DateTime($options['ends_at']));
        }

        foreach ($options['channels'] as $channel) {
            $callout->addChannel($channel);
        }

        foreach ($options['rules'] as $rule) {
            /** @var CalloutRuleInterface $calloutRule */
            $calloutRule = $this->calloutRuleExampleFactory->create($rule);
            $callout->addRule($calloutRule);
        }

        $callout->setEnabled($options['enabled']);

        return $callout;
    }

    protected function createTranslation(CalloutInterface $callout, string $localeCode, array $options = []): void
    {
        $options = $this->optionsResolver->resolve($options);

        $callout->setCurrentLocale($localeCode);
        $callout->setFallbackLocale($localeCode);

        $callout->setText($options['text']);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', $this->faker->words(3, true))

            ->setDefault('code', function (Options $options): string {
                return StringInflector::nameToCode($options['name']);
            })

            ->setDefault('text', function (Options $options): string {
                return $options['name'];
            })

            ->setDefault('translations', [])
            ->setAllowedTypes('translations', ['array'])

            ->setDefined('position')
            ->setAllowedValues('position', Callout::getAllowedPositions() + [\Closure::class])
            ->setDefault('position', $this->faker->randomElement(Callout::getAllowedPositions()))

            ->setDefault('priority', 0)

            ->setDefault('starts_at', null)
            ->setAllowedTypes('starts_at', ['null', 'string', \DateTimeInterface::class])

            ->setDefault('ends_at', null)
            ->setAllowedTypes('ends_at', ['null', 'string', \DateTimeInterface::class])

            ->setDefault('channels', LazyOption::all($this->channelRepository))
            ->setAllowedTypes('channels', ['array'])
            ->setNormalizer('channels', LazyOption::findBy($this->channelRepository, 'code'))

            ->setDefined('rules')
            ->setNormalizer('rules', function (Options $options, array $rules): array {
                if ($rules === []) {
                    return [[]];
                }

                return $rules;
            })

            ->setDefault('enabled', true)
            ->setAllowedTypes('enabled', ['boolean'])
        ;
    }

    private function getLocales(): iterable
    {
        /** @var LocaleInterface[] $locales */
        $locales = $this->localeRepository->findAll();
        foreach ($locales as $locale) {
            yield $locale->getCode();
        }
    }
}
