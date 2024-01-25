<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\Persistence\ManagerRegistry;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Event\ProductQueryBuilderEvent;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;

class AbstractAssignCalloutsHandler
{
    use ORMManagerTrait;

    public function __construct(
        protected readonly CalloutRepositoryInterface $calloutRepository,
        protected readonly EventDispatcherInterface $eventDispatcher,
        protected readonly CalloutEligibilityCheckerInterface $eligibilityChecker,
        ManagerRegistry $managerRegistry,
        /** @var class-string $productClass */
        protected readonly string $productClass,
    ) {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param iterable<ProductInterface> $products If the $products argument is empty, all products will be fetched from the database
     * @param iterable<CalloutInterface> $callouts  If the $callouts argument is empty, all callouts will be fetched from the database
     */
    protected function assign(iterable $products = [], iterable $callouts = []): void
    {
        $manager = $this->getManager($this->productClass);

        if ([] === $products) {
            $qb = $manager
                ->createQueryBuilder()
                ->select('o')
                ->from($this->productClass, 'o')
                // todo the following two 'wheres' should be moved to event subscribers and be able to be disabled in the configuration of the plugin
                ->andWhere('o.enabled = true')
                ->andWhere('SIZE(o.channels) > 0')
            ;

            $this->eventDispatcher->dispatch(new ProductQueryBuilderEvent($qb));

            /** @var iterable<ProductInterface> $products */
            $products = SimpleBatchIteratorAggregate::fromQuery($qb->getQuery(), 100);
        }

        $resetCallouts = false;
        // we know that the only case where we want to reset the callouts is when we assign _all_ callouts
        if ([] === $callouts) {
            $callouts = $this->calloutRepository->findEnabled();
            $resetCallouts = true;
        }

        foreach ($products as $product) {
            if ($resetCallouts) {
                $product->resetPreQualifiedCallouts();
            }

            foreach ($callouts as $callout) {
                $product->removePreQualifiedCallout($callout);
                if ($this->eligibilityChecker->isEligible($product, $callout)) {
                    $product->addPreQualifiedCallout($callout);
                }
            }
        }

        $manager->flush();
    }
}
