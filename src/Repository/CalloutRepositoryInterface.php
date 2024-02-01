<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface CalloutRepositoryInterface extends RepositoryInterface
{
    public function findOneByCode(string $code): ?CalloutInterface;

    /**
     * @param list<string> $codes If the codes array is empty, all enabled callouts will be returned else only the enabled callouts with the given codes
     *
     * @return list<CalloutInterface>
     */
    public function findEnabled(array $codes = []): array;

    /**
     * @param list<string> $codes
     *
     * @return CalloutInterface[]
     */
    public function findByCodes(array $codes, ChannelInterface $channel, string $locale): array;
}
