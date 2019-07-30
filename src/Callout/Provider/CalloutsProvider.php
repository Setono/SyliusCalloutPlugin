<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Provider;

use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;

final class CalloutsProvider implements PreQualifiedCalloutsProviderInterface
{
    /** @var CalloutRepositoryInterface */
    private $calloutRepository;

    public function __construct(CalloutRepositoryInterface $calloutRepository)
    {
        $this->calloutRepository = $calloutRepository;
    }

    public function getCallouts(): array
    {
        return $this->calloutRepository->findOrdered();
    }
}
