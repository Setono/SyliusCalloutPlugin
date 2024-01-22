<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Provider;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;

final class CalloutProvider implements CalloutProviderInterface
{
    /**
     * A cache of callouts, indexed by code
     *
     * @var array<string, CalloutInterface>
     */
    private array $callouts = [];

    public function __construct(private readonly CalloutRepositoryInterface $calloutRepository)
    {
    }

    public function getByCodes(array $codes): array
    {
        $missingCodes = [];
        foreach ($codes as $code) {
            if (!isset($this->callouts[$code])) {
                $missingCodes[] = $code;
            }
        }

        if ([] !== $missingCodes) {
            $missingCallouts = $this->calloutRepository->findByCodes($missingCodes);
            foreach ($missingCallouts as $callout) {
                $this->callouts[(string) $callout->getCode()] = $callout;
            }
        }

        $callouts = [];

        foreach ($codes as $code) {
            $callouts[] = $this->callouts[$code];
        }

        return $callouts;
    }
}
