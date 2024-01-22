<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

/**
 * Dispatch this message to trigger the assigning of the given callout to products.
 * This is useful if you have changed the rules of a callout and want to re-assign it to products.
 */
final class AssignCallout implements CommandInterface
{
    /**
     * This is the callout code
     */
    public string $callout;

    public ?int $version = null;

    public function __construct(string|CalloutInterface $callout)
    {
        if ($callout instanceof CalloutInterface) {
            $this->version = $callout->getVersion();
            $callout = (string) $callout->getCode();
        }

        $this->callout = $callout;
    }
}
