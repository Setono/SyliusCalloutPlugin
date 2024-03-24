<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCalloutPlugin\Twig;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Checker\RenderingEligibility\CalloutRenderingEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\CssBuilder\CssClassBuilderInterface;
use Setono\SyliusCalloutPlugin\CssBuilder\CssStyleBuilderInterface;
use Setono\SyliusCalloutPlugin\Model\Callout;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Provider\RenderingCalloutProviderInterface;
use Setono\SyliusCalloutPlugin\Renderer\CalloutRendererInterface;
use Setono\SyliusCalloutPlugin\Twig\CalloutRuntime;

final class CalloutRuntimeTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_returns_callouts(): void
    {
        $runtime = $this->getCalloutRuntime();

        $product = $this->prophesize(ProductInterface::class);
        $product->getPreQualifiedCallouts()->willReturn(['callout_1', 'callout_2', 'callout_3']);

        $callouts = $runtime->getCallouts($product->reveal());
        $calloutsDefaultElement = $runtime->getCallouts($product->reveal(), CalloutInterface::DEFAULT_KEY);

        self::assertArrayHasKey(CalloutInterface::DEFAULT_KEY, $callouts); // element
        self::assertIsArray($callouts[CalloutInterface::DEFAULT_KEY]);
        self::assertEquals($callouts[CalloutInterface::DEFAULT_KEY], $calloutsDefaultElement); // this tests that the filter on the CalloutRuntime::getCallouts() method works
        self::assertArrayHasKey('top', $callouts[CalloutInterface::DEFAULT_KEY]); // top position
        self::assertArrayHasKey('bottom', $callouts[CalloutInterface::DEFAULT_KEY]); // top position

        $topCallout = $callouts[CalloutInterface::DEFAULT_KEY]['top'];
        $bottomCallout = $callouts[CalloutInterface::DEFAULT_KEY]['bottom'];

        self::assertInstanceOf(CalloutInterface::class, $topCallout);
        self::assertInstanceOf(CalloutInterface::class, $bottomCallout);

        self::assertSame('callout_3', $topCallout->getCode());
        self::assertSame('callout_2', $bottomCallout->getCode());
    }

    private function getCalloutRuntime(): CalloutRuntime
    {
        $calloutRenderingEligibilityChecker = $this->prophesize(CalloutRenderingEligibilityCheckerInterface::class);
        $calloutRenderingEligibilityChecker->isEligible(Argument::type(CalloutInterface::class))->willReturn(true);

        $calloutEligibilityChecker = $this->prophesize(CalloutEligibilityCheckerInterface::class);
        $calloutEligibilityChecker
            ->isRuntimeEligible(
                Argument::type(ProductInterface::class),
                Argument::type(CalloutInterface::class),
            )->willReturn(true)
        ;

        $cssClassBuilder = $this->prophesize(CssClassBuilderInterface::class);

        $renderCalloutProvider = $this->prophesize(RenderingCalloutProviderInterface::class);
        $renderCalloutProvider
            ->getByCodes(['callout_1', 'callout_2', 'callout_3'])
            ->willReturn([
                self::createCallout('callout_1', 'top'),
                self::createCallout('callout_2', 'bottom'),
                self::createCallout('callout_3', 'top', 10), // this callout should overwrite callout_1 because it has a higher priority
            ])
        ;

        $calloutRenderer = $this->prophesize(CalloutRendererInterface::class);
        $cssStyleBuilder = $this->prophesize(CssStyleBuilderInterface::class);

        return new CalloutRuntime(
            $calloutRenderingEligibilityChecker->reveal(),
            $calloutEligibilityChecker->reveal(),
            $cssClassBuilder->reveal(),
            $renderCalloutProvider->reveal(),
            $calloutRenderer->reveal(),
            $cssStyleBuilder->reveal(),
        );
    }

    private static function createCallout(string $code, string $position, int $priority = 0): CalloutInterface
    {
        $callout = new Callout();
        $callout->setCode($code);
        $callout->setposition($position);
        $callout->setPriority($priority);

        return $callout;
    }
}
