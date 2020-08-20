<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Command;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Webmozart\Assert\Assert;

abstract class AbstractCalloutCommand
{
    /** @var int */
    protected $calloutId;

    /**
     * @param int|CalloutInterface $callout
     */
    public function __construct($callout)
    {
        if ($callout instanceof CalloutInterface) {
            $callout = (int) $callout->getId();
        }

        Assert::integer($callout);

        $this->calloutId = $callout;
    }

    public function getCalloutId(): int
    {
        return $this->calloutId;
    }
}
