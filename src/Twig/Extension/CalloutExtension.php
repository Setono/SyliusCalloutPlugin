<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig\Extension;

use Doctrine\Common\Collections\Collection;
use function Safe\preg_replace;
use function Safe\sprintf;
use Setono\SyliusCalloutPlugin\Callout\Checker\RenderingEligibility\RenderingCalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Callout\CssClassBuilderInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class CalloutExtension extends AbstractExtension
{
    /** @var RenderingCalloutEligibilityCheckerInterface */
    private $renderingCalloutEligibilityChecker;

    /** @var CssClassBuilderInterface */
    private $cssClassBuilder;

    public function __construct(
        RenderingCalloutEligibilityCheckerInterface $renderingCalloutEligibilityChecker,
        CssClassBuilderInterface $cssClassBuilder
    ) {
        $this->renderingCalloutEligibilityChecker = $renderingCalloutEligibilityChecker;
        $this->cssClassBuilder = $cssClassBuilder;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('setono_callouts', [$this, 'filterCallouts']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setono_callout_classes', [$this, 'calloutClasses']),
        ];
    }

    /**
     * @param Collection|CalloutInterface[] $callouts
     *
     * @return Collection|CalloutInterface[]
     */
    public function filterCallouts(Collection $callouts): Collection
    {
        if ($callouts->isEmpty()) {
            return $callouts;
        }

        return $callouts->filter(function (CalloutInterface $callout) {
            return $this->renderingCalloutEligibilityChecker->isEligible($callout);
        });
    }

    public function calloutClasses(CalloutInterface $callout): string
    {
        $classes = [
            $this->cssClassBuilder->buildClasses($callout),
            'setono-callout',
            sprintf(
                'setono-callout-code-%s',
                $this->sanitizeClass((string) $callout->getCode())
            ),
        ];

        if ($callout->getPosition() !== null) {
            $classes[] = sprintf(
                'setono-callout-position-%s',
                $this->sanitizeClass($callout->getPosition())
            );
        }

        return implode(' ', $classes);
    }

    private function sanitizeClass(string $str): string
    {
        return strtolower(preg_replace('/[^0-9A-Za-z\-]+/', '-', $str));
    }
}
