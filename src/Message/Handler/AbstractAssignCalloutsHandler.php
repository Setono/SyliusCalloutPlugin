<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusCalloutPlugin\BatchIterator\BatchIteratorFactoryInterface;
use Setono\SyliusCalloutPlugin\Checker\Eligibility\CalloutEligibilityCheckerInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Repository\CalloutRepositoryInterface;

class AbstractAssignCalloutsHandler
{
    use ORMManagerTrait;

    public function __construct(
        protected readonly CalloutRepositoryInterface $calloutRepository,
        protected readonly CalloutEligibilityCheckerInterface $eligibilityChecker,
        protected readonly BatchIteratorFactoryInterface $batchIteratorFactory,
        ManagerRegistry $managerRegistry,
        /** @var class-string<ProductInterface> $productClass */
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
            $products = $this->batchIteratorFactory
                ->create($this->productClass)
                ->modify(function (QueryBuilder $qb): void {
                    $qb
                        ->andWhere('o.enabled = true')
                        ->andWhere('SIZE(o.channels) > 0')
                    ;
                })
            ;
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
