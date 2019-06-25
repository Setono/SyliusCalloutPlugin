<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Setono\SyliusCalloutPlugin\Message\Command\AssignProductCallouts;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Provider\CalloutProviderInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AssignProductCalloutsHandler implements MessageHandlerInterface
{
    /** @var CalloutProviderInterface */
    private $calloutProvider;

    /** @var EntityManagerInterface */
    private $productManager;

    /** @var string */
    private $productClass;

    public function __construct(
        CalloutProviderInterface $calloutProvider,
        EntityManagerInterface $productManager,
        string $productClass
    ) {
        $this->calloutProvider = $calloutProvider;
        $this->productManager = $productManager;
        $this->productClass = $productClass;
    }

    public function __invoke(AssignProductCallouts $message): void
    {
        $qb = $this->productManager->createQueryBuilder();
        $qb->select('o')
            ->from($this->productClass, 'o')
            ->andWhere('o.enabled = 1')
            ->andWhere('o.id >= :lowerBound')
            ->andWhere('o.id <= :upperBound')
            ->setParameters([
                'lowerBound' => $message->getBatch()->getLowerBound(),
                'upperBound' => $message->getBatch()->getUpperBound(),
            ])
        ;

        /** @var ProductInterface[] $products */
        $products = $qb->getQuery()->getResult();

        if (count($products) === 0) {
            return;
        }

        foreach ($products as $product) {
            $callouts = $this->calloutProvider->getCallouts($product);

            $product->setCallouts($callouts);
        }

        $this->productManager->flush();
    }
}
