<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;

abstract class AbstractCalloutCommand
{
    /** @var mixed|CalloutInterface */
    protected $calloutId;

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
