<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Event\ProductQueryBuilderEvent;
use Setono\SyliusCalloutPlugin\Message\Command\AssignCallout;
use Setono\SyliusCalloutPlugin\Message\Command\AssignCallouts;
use Setono\SyliusCalloutPlugin\Message\Command\AssignCalloutsToProduct;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Webmozart\Assert\Assert;

final class AssignCalloutsHandler
{
    use ORMManagerTrait;

    public function __construct(
        private readonly CalloutRepositoryInterface $calloutRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CalloutEligibilityCheckerInterface $eligibilityChecker,
        ManagerRegistry $managerRegistry,
        /** @var class-string $productClass */
        private readonly string $productClass,
    ) {
        $this->managerRegistry = $managerRegistry;
    }

    public function __invoke(AssignCallouts|AssignCallout|AssignCalloutsToProduct $message): void
    {
        if ($message instanceof AssignCallout) {
            $callout = $this->calloutRepository->findOneByCode($message->callout);
            if (null === $callout) {
                throw new UnrecoverableMessageHandlingException(sprintf('Callout with code "%s" does not exist', $message->callout));
            }

            if (null !== $message->version && $message->version !== $callout->getVersion()) {
                // this means the callout has been updated since the message was sent
                throw new UnrecoverableMessageHandlingException(sprintf('Callout with id %s has version %s, but version %s was expected', $message->callout, (string) $callout->getVersion(), $message->version));
            }

            if (!$callout->isEnabled()) {
                throw new UnrecoverableMessageHandlingException(sprintf('Callout with id %s is not enabled', $message->callout));
            }

            $callouts = [$callout];
        } else {
            $callouts = $this->calloutRepository->findEnabled($message instanceof AssignCallouts ? $message->callouts : []);
        }

        $manager = $this->getManager($this->productClass);
        if ($message instanceof AssignCalloutsToProduct) {
            /** @var ProductRepositoryInterface|EntityRepository $productRepository */
            $productRepository = $manager->getRepository($this->productClass);
            Assert::isInstanceOf($productRepository, ProductRepositoryInterface::class);

            $product = $productRepository->findOneByCode($message->product);
            if (!$product instanceof ProductInterface) {
                throw new UnrecoverableMessageHandlingException(sprintf('Product with code "%s" does not exist', $message->product));
            }

            $products = [$product];
        } else {
            $qb = $manager
                ->createQueryBuilder()
                ->select('o')
                ->from($this->productClass, 'o')
                // todo the following two 'wheres' should be moved to event subscribers and be able to be disabled in the configuration of the plugin
                ->andWhere('o.enabled = true')
                ->andWhere('SIZE(o.channels) > 0')
            ;

            $this->eventDispatcher->dispatch(new ProductQueryBuilderEvent($qb));

            /** @var list<ProductInterface> $products */
            $products = SimpleBatchIteratorAggregate::fromQuery($qb->getQuery(), 100);
        }

        foreach ($products as $product) {
            // only when we test all callouts we need to reset the pre-qualified callouts
            if ($message instanceof AssignCallout) {
                $product->removePreQualifiedCallout($message->callout);
            } else {
                $product->resetPreQualifiedCallouts();
            }

            foreach ($callouts as $callout) {
                if ($this->eligibilityChecker->isEligible($product, $callout)) {
                    $product->addPreQualifiedCallout($callout);
                }
            }
        }

        $manager->flush();
    }
}
