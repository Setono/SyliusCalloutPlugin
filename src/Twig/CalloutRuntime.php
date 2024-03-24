<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Twig;

use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Checker\RenderingEligibility\CalloutRenderingEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\CssBuilder\CssClassBuilderInterface;
use Setono\SyliusCalloutPlugin\CssBuilder\CssStyleBuilderInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Provider\RenderingCalloutProviderInterface;
use Setono\SyliusCalloutPlugin\Renderer\CalloutRendererInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class CalloutRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly CalloutRenderingEligibilityCheckerInterface $calloutRenderingEligibilityChecker,
        private readonly CalloutEligibilityCheckerInterface $calloutEligibilityChecker,
        private readonly CssClassBuilderInterface $cssClassBuilder,
        private readonly RenderingCalloutProviderInterface $renderingCalloutProvider,
        private readonly CalloutRendererInterface $calloutRenderer,
        private readonly CssStyleBuilderInterface $cssStyleBuilder,
    ) {
    }

    /**
     * Notice that this method will return _only_ one callout per element and position
     *
     * @return array<string, CalloutInterface|array<string, CalloutInterface>>
     */
    public function getCallouts(ProductInterface $product, string $element = null): array
    {
        /** @var array<string, array<string, CalloutInterface>> $callouts */
        $callouts = [];

        $preQualifiedCallouts = $this->renderingCalloutProvider->getByCodes($product->getPreQualifiedCallouts());

        // the reason we sort ascending is that we want the last one to be the one with the highest priority when we add the callouts to the resulting array
        usort($preQualifiedCallouts, static fn (CalloutInterface $a, CalloutInterface $b) => $a->getPriority() <=> $b->getPriority());

        foreach ($preQualifiedCallouts as $callout) {
            if (!$this->calloutRenderingEligibilityChecker->isEligible($callout)) {
                continue;
            }

            if (!$this->calloutEligibilityChecker->isRuntimeEligible($product, $callout)) {
                continue;
            }

            $elements = $callout->getElements();
            if ([] === $elements) {
                $elements = [CalloutInterface::DEFAULT_KEY];
            }

            $position = $callout->getPosition() ?? CalloutInterface::DEFAULT_KEY;

            foreach ($elements as $elm) {
                $callouts[$elm][$position] = $callout;
            }
        }

        if (null !== $element) {
            return $callouts[$element] ?? [];
        }

        return $callouts;
    }

    public function renderCalloutClassAttribute(CalloutInterface $callout): string
    {
        return $this->cssClassBuilder->build($callout);
    }

    public function renderCallout(CalloutInterface $callout): string
    {
        return (string) $this->calloutRenderer->render($callout);
    }

    public function renderCalloutStyle(CalloutInterface $callout): string
    {
        return $this->cssStyleBuilder->build($callout);
    }
}
