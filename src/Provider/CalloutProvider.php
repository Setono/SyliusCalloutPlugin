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
     * @var array<string, CalloutInterface|null>
     */
    private array $callouts = [];

    public function __construct(private readonly CalloutRepositoryInterface $calloutRepository)
    {
    }

    public function getByCodes(array $codes): array
    {
        $missingCodes = [];
        foreach ($codes as $code) {
            if (!array_key_exists($code, $this->callouts)) {
                $missingCodes[] = $code;
                $this->callouts[$code] = null;
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

        return array_values(array_filter($callouts));
    }
}
