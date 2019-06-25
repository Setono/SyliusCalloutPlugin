<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

/**
 * Will assign the given callout to products where the callout is eligible
 */
final class AssignCalloutToProducts
{
    /** @var mixed|CalloutInterface */
    private $calloutId;

    /**
     * @param mixed|CalloutInterface $callout
     */
    public function __construct($callout)
    {
        $this->calloutId = $callout instanceof CalloutInterface ? $callout->getId() : $callout;
    }

    public function getCalloutId()
    {
        return $this->calloutId;
    }
}
