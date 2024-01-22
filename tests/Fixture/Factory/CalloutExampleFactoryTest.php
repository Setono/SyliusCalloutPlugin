<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Fixture\Factory;

use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Setono\SyliusCalloutPlugin\Factory\CalloutRuleFactory;
use Setono\SyliusCalloutPlugin\Fixture\Factory\CalloutExampleFactory;
use Setono\SyliusCalloutPlugin\Fixture\Factory\CalloutRuleExampleFactory;
use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutRule;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Locale\Model\Locale;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class CalloutExampleFactoryTest extends TestCase
{
    private CalloutExampleFactory $calloutExampleFactory;

    /** @test */
    public function create_callout_without_rules(): void
    {
        $callout = $this->calloutExampleFactory->create(['name' => 'Callout without rules', 'rules' => []]);

        self::assertEquals('Callout without rules', $callout->getName());
        self::assertCount(0, $callout->getRules());
    }

    protected function setUp(): void
    {
        $calloutRuleFactory = new CalloutRuleFactory(new Factory(CalloutRule::class));
        $calloutRuleExampleFactory = new CalloutRuleExampleFactory($calloutRuleFactory);
        $calloutFactory = new Factory(Callout::class);
        $calloutManager = $this->createMock(ObjectManager::class);
        $channelRepository = $this->createMock(ChannelRepositoryInterface::class);

        $channel = new Channel();
        $channel->setCode('default');

        $channelRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn([$channel]);

        $localeRepository = $this->createMock(RepositoryInterface::class);

        $locale = new Locale();
        $locale->setCode('en_US');

        $localeRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn([$locale]);

        $this->calloutExampleFactory = new CalloutExampleFactory(
            $calloutFactory,
            $calloutManager,
            $calloutRuleExampleFactory,
            $channelRepository,
            $localeRepository,
            ['top', 'bottom'],
        );
    }
}
