<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;
use Webmozart\Assert\Assert;

final class CalloutContext implements Context
{
    public function __construct(private readonly CalloutRepositoryInterface $calloutRepository)
    {
    }

    /**
     * @Transform /^callout "([^"]+)"$/
     * @Transform /^"([^"]+)" callout/
     * @Transform :callout
     */
    public function getCalloutByName(string $calloutName): CalloutInterface
    {
        $callouts = $this->calloutRepository->findBy([
            'name' => $calloutName,
        ]);

        Assert::eq(
            count($callouts),
            1,
            sprintf('%d callouts has been found with name "%s".', count($callouts), $calloutName),
        );

        return $callouts[0];
    }
}
