<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Persistence\ObjectManager;
use Setono\SyliusCalloutPlugin\Factory\CalloutRuleFactoryInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class ProductCalloutContext implements Context
{
    public function __construct(
        private readonly CalloutRuleFactoryInterface $calloutRuleFactory,
        private readonly FactoryInterface $calloutFactory,
        private readonly ObjectManager $objectManager,
        private readonly SharedStorageInterface $sharedStorage,
    ) {
    }

    /**
     * @Given /^there is a callout "([^"]+)" with "Has taxon" rule configured with ("[^"]+" taxon) and with "([^"]+)" html$/
     * @Given /^there is a callout "([^"]+)" with "Has taxon" rule configured with ("[^"]+" taxon) and with "([^"]+)" html in ("[^"]+" channel)$/
     */
    public function thereIsAProductCalloutWithRuleConfiguredWithTaxon(string $name, TaxonInterface $taxon, string $html, ChannelInterface $channel = null): void
    {
        $callout = $this->createCallout($name, $html, $channel);
        $callout->addRule($this->calloutRuleFactory->createHasTaxon([$taxon]));

        $this->objectManager->persist($callout);
        $this->objectManager->flush();
    }

    /**
     * @Given /^there is a callout "([^"]+)" with "Has product" rule configured with ("[^"]+" product) and with "([^"]+)" html$/
     * @Given /^there is a callout "([^"]+)" with "Has product" rule configured with ("[^"]+" product) and with "([^"]+)" html in ("[^"]+" channel)$/
     */
    public function thereIsAProductCalloutWithRuleConfiguredWithProduct(string $name, ProductInterface $product, string $html, ChannelInterface $channel = null): void
    {
        $callout = $this->createCallout($name, $html, $channel);
        $callout->addRule($this->calloutRuleFactory->createHasProduct([$product]));

        $this->objectManager->persist($callout);
        $this->objectManager->flush();
    }

    /**
     * @Given /^there is a callout "([^"]+)" with "Is new" rule configured with (\d+) days? and with "([^"]+)" html$/
     * @Given /^there is a callout "([^"]+)" with "Is new" rule configured with (\d+) days? and with "([^"]+)" html in ("[^"]+" channel)$/
     */
    public function thereIsAnIsNewProductCalloutWithRuleConfiguredWithDays(string $name, string $days, string $html, ChannelInterface $channel = null): void
    {
        $callout = $this->createCallout($name, $html, $channel);
        $callout->addRule($this->calloutRuleFactory->createIsNewProduct((int) $days));

        $this->objectManager->persist($callout);
        $this->objectManager->flush();
    }

    /**
     * @Given /^there is a callout "([^"]+)" with "([^"]+)" html$/
     * @Given /^there is a callout "([^"]+)" with "([^"]+)" html in ("[^"]+" channel)$/
     */
    public function thereIsCalloutWithoutRules(string $name, string $html, ChannelInterface $channel = null): void
    {
        $callout = $this->createCallout($name, $html, $channel);

        $this->objectManager->persist($callout);
        $this->objectManager->flush();
    }

    /**
     * @Given /^(the callout "([^"]+)") is disabled for ("[^"]+" channel)$/
     * @Given /^(the callout "([^"]+)") is disabled for (this channel)$/
     * @Given /^(this callout) is disabled for ("[^"]+" channel)$/
     * @Given /^(this callout) is disabled for (this channel)$/
     */
    public function calloutDisabledForChannel(CalloutInterface $callout, ChannelInterface $channel): void
    {
        $callout->removeChannel($channel);

        $this->objectManager->flush();
    }

    private function createCallout(string $name, string $html, ChannelInterface $channel = null): CalloutInterface
    {
        /** @var CalloutInterface $callout */
        $callout = $this->calloutFactory->createNew();
        $this->sharedStorage->set('callout', $callout);

        $channel = $channel ?? $this->getChannel();

        if (null !== $channel) {
            $callout->addChannel($channel);

            foreach ($channel->getLocales() as $locale) {
                $callout->setCurrentLocale((string) $locale->getCode());
                $callout->setFallbackLocale((string) $locale->getCode());

                $callout->setName($name);
                $callout->setText($html);
            }
        }

        $callout->setCode(StringInflector::nameToCode($name));
        $callout->setPosition('top_left');
        $callout->setPriority(0);
        $callout->setEnabled(true);

        return $callout;
    }

    private function getChannel(): ?ChannelInterface
    {
        if (!$this->sharedStorage->has('channel')) {
            return null;
        }

        $channel = $this->sharedStorage->get('channel');
        Assert::isInstanceOf($channel, ChannelInterface::class);

        return $channel;
    }
}
