<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;
use Webmozart\Assert\Assert;

final class CalloutContext implements Context
{
    /** @var CalloutRepositoryInterface */
    private $calloutRepository;

    public function __construct(CalloutRepositoryInterface $calloutRepository)
    {
        $this->calloutRepository = $calloutRepository;
    }

    /**
     * @Transform /^callout "([^"]+)"$/
     * @Transform /^"([^"]+)" callout/
     * @Transform :callout
     */
    public function getCalloutByName($calloutName)
    {
        $callouts = $this->calloutRepository->findByName($calloutName);

        Assert::eq(
            count($callouts),
            1,
            sprintf('%d callouts has been found with name "%s".', count($callouts), $calloutName)
        );

        return $callouts[0];
    }

    /**
     * @Transform all callouts
     */
    public function getAllChannels()
    {
        return $this->channelRepository->findAll();
    }
}
