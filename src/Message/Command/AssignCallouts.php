<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

/**
 * Dispatch this message to trigger the assigning of pre-qualified callouts to products
 */
final class AssignCallouts implements CommandInterface
{
    /** @var list<string> */
    public array $callouts = [];

    /**
     * @param list<CalloutInterface> $callouts If the callouts array is empty, all callouts will be assigned
     */
    public function __construct(array $callouts = [])
    {
        foreach ($callouts as $callout) {
            $this->callouts[] = (string) $callout->getCode();
        }
    }
}
