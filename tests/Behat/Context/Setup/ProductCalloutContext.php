<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Setono\SyliusCalloutPlugin\Assigner\CalloutAssignerInterface;
use Setono\SyliusCalloutPlugin\Factory\CalloutRuleFactoryInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductCalloutContext implements Context
{
    /** @var CalloutRuleFactoryInterface */
    private $calloutRuleFactory;

    /** @var CalloutAssignerInterface */
    private $productCalloutsAssigner;

    /** @var FactoryInterface */
    private $calloutFactory;

    /** @var ObjectManager */
    private $objectManager;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    public function __construct(
        CalloutRuleFactoryInterface $calloutRuleFactory,
        CalloutAssignerInterface $productCalloutsAssigner,
        FactoryInterface $calloutFactory,
        ObjectManager $objectManager,
        SharedStorageInterface $sharedStorage
    ) {
        $this->calloutRuleFactory = $calloutRuleFactory;
        $this->productCalloutsAssigner = $productCalloutsAssigner;
        $this->calloutFactory = $calloutFactory;
        $this->objectManager = $objectManager;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given /^there is a product callout "([^"]+)" with "Has taxon" rule configured with ("[^"]+" taxon) and with "([^"]+)" html$/
     */
    public function thereIsAProductCalloutWithRuleConfiguredWithTaxon(string $name, TaxonInterface $taxon, string $html): void
    {
        /** @var CalloutInterface $callout */
        $callout = $this->calloutFactory->createNew();

        /** @var ChannelInterface $channel */
        $channel = $this->sharedStorage->get('channel');

        $callout->setCode(StringInflector::nameToCode($name));
        $callout->setPosition(CalloutInterface::POSITION_TOP_LEFT);
        $callout->setPriority(0);
        $callout->setEnabled(true);

        foreach ($channel->getLocales() as $locale) {
            $callout->setCurrentLocale($locale->getCode());
            $callout->setFallbackLocale($locale->getCode());

            $callout->setName($name);
            $callout->setText($html);
        }

        $callout->addRule($this->calloutRuleFactory->createHasTaxon([$taxon]));

        $this->objectManager->persist($callout);
        $this->objectManager->flush();
        $this->objectManager->clear();

        $this->productCalloutsAssigner->assign();
    }
}
