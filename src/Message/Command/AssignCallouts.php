<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

/**
 * Dispatch this message to trigger the assigning of pre-qualified callouts to products
 */
final class AssignCallouts implements CommandInterface
{
    /**
     * This is a list of arrays where the first element is the callout code and the second element is the version
     *
     * @var list<array{string, int|null}>
     */
    public array $callouts = [];

    /**
     * @param list<CalloutInterface|string> $callouts If the callouts array is empty, all callouts will be assigned
     */
    public function __construct(array $callouts = [])
    {
        foreach ($callouts as $callout) {
            $this->callouts[] = $callout instanceof CalloutInterface ? [(string) $callout->getCode(), $callout->getVersion()] : [$callout, null];
        }
    }
}
