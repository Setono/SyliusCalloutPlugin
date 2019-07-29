<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig\Extension;

use Doctrine\Common\Collections\Collection;
use Exception;
use function Safe\preg_replace;
use function Safe\sprintf;
use Setono\SyliusCalloutPlugin\Callout\Checker\RenderingEligibility\RenderingCalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class CalloutExtension extends AbstractExtension
{
    /** @var RenderingCalloutEligibilityCheckerInterface */
    private $renderingCalloutEligibilityChecker;

    public function __construct(RenderingCalloutEligibilityCheckerInterface $renderingCalloutEligibilityChecker)
    {
        $this->renderingCalloutEligibilityChecker = $renderingCalloutEligibilityChecker;
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
            'red', // @todo Add color field to Callout
            $this->getSemanticUiClassesFromPosition($callout->getPosition()),
            'setono-callout',
            'setono-callout-code-' . $this->sanitizeClass((string) $callout->getCode()),
        ];

        if ($callout->getPosition() !== null) {
            $classes[] = 'setono-callout-position-' . $this->sanitizeClass($callout->getPosition());
        }

        return implode(' ', $classes);
    }

    private function getSemanticUiClassesFromPosition(string $position): string
    {
        static $semanticUiPositionClassMap = [
            CalloutInterface::POSITION_TOP_LEFT => 'top left attached label',
            CalloutInterface::POSITION_BOTTOM_LEFT => 'bottom left attached label',
            CalloutInterface::POSITION_TOP_RIGHT => 'top right attached label',
            CalloutInterface::POSITION_BOTTOM_RIGHT => 'bottom right attached label',
        ];

        if (!array_key_exists($position, $semanticUiPositionClassMap)) {
            throw new Exception(sprintf(
                'Unable to translate position "%s" to classes',
                $position
            ));
        }

        return $semanticUiPositionClassMap[$position];
    }

    private function sanitizeClass(string $str): string
    {
        return strtolower(preg_replace('/[^0-9A-Za-z\-]+/', '-', $str));
    }
}
