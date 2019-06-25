<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Setono\SyliusCalloutPlugin\Message\Command\AssignCalloutToProducts;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class AssignCalloutToProductsHandler implements MessageHandlerInterface
{
    /** @var CalloutRepositoryInterface */
    private $calloutRepository;

    public function __construct(CalloutRepositoryInterface $calloutRepository)
    {
        $this->calloutRepository = $calloutRepository;
    }

    public function __invoke(AssignCalloutToProducts $message): void
    {
        /** @var CalloutInterface|null $callout */
        $callout = $this->calloutRepository->find($message->getCalloutId());

        Assert::isInstanceOf($callout, CalloutInterface::class);

        // todo implement this
    }
}
