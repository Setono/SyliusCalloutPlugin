<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Callout\Checker\Rule;

use DateTimeInterface;
use Safe\DateTime;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Promotion\Exception\UnsupportedTypeException;
use Webmozart\Assert\Assert;

final class IsNewProductCalloutRuleChecker implements ProductCalloutRuleCheckerInterface
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

    public function isEligible(CalloutsAwareInterface $product, array $configuration): bool
    {
        if (!$product instanceof ProductInterface) {
            throw new UnsupportedTypeException($product, ProductInterface::class);
        }

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
