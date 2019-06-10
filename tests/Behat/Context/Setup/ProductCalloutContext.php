<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutsPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Setono\SyliusCalloutsPlugin\Assigner\ProductCalloutsAssignerInterface;
use Setono\SyliusCalloutsPlugin\Factory\CalloutRuleFactoryInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductCalloutContext implements Context
{
    /** @var CalloutRuleFactoryInterface */
    private $calloutRuleFactory;

    /** @var ProductCalloutsAssignerInterface */
    private $productCalloutsAssigner;

    /** @var FactoryInterface */
    private $calloutFactory;

    /** @var ObjectManager */
    private $objectManager;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    public function __construct(
        CalloutRuleFactoryInterface $calloutRuleFactory,
        ProductCalloutsAssignerInterface $productCalloutsAssigner,
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

        $callout->setCode(md5($name));
        $callout->setPosition(CalloutInterface::TOP_LEFT_CORNER_POSITION);
        $callout->setPriority(0);
        $callout->setEnabled(true);

        foreach ($channel->getLocales() as $locale) {
            $callout->setCurrentLocale($locale->getCode());
            $callout->setFallbackLocale($locale->getCode());

            $callout->setName($name);
            $callout->setHtml($html);
        }

        $callout->addRule($this->calloutRuleFactory->createHasTaxon([$taxon]));

        $this->objectManager->persist($callout);
        $this->objectManager->flush();
        $this->objectManager->clear();

        $this->productCalloutsAssigner->assign();
    }
}
