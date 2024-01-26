<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Checker\Rule;

use DateTime;
use DateTimeInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class IsNewCalloutRuleChecker extends AbstractCalloutRuleChecker
{
    public const TYPE = 'is_new';

    /** @var DateTimeInterface */
    private $now;

    public function __construct(?DateTimeInterface $now = null)
    {
        if (null === $now) {
            $now = new DateTime('now');
        }

        $this->now = $now;
    }

    public function isEligible(ProductInterface $product, array $configuration): bool
    {
        Assert::keyExists($configuration, 'days');
        Assert::numeric($configuration['days']);

        $createdAt = $product->getCreatedAt();
        if (null === $createdAt) {
            return false;
        }

        $interval = $this->now->diff($createdAt);

        return (int) $interval->format('%a') <= (int) $configuration['days'];
    }
}
