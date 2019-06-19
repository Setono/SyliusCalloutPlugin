<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Message\Handler;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Setono\SyliusCalloutsPlugin\Message\Command\AssignProductCallouts;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutsPlugin\Model\ProductInterface;
use Setono\SyliusCalloutsPlugin\Provider\CalloutProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class AssignProductCalloutsHandler implements MessageHandlerInterface
{
    /** @var CalloutProviderInterface */
    private $calloutProvider;

    /** @var RepositoryInterface */
    private $productRepository;

    /** @var EntityManagerInterface */
    private $productManager;

    public function __construct(
        CalloutProviderInterface $calloutProvider,
        RepositoryInterface $productRepository,
        EntityManagerInterface $productManager
    ) {
        $this->calloutProvider = $calloutProvider;
        $this->productRepository = $productRepository;
        $this->productManager = $productManager;
    }

    public function __invoke(AssignProductCallouts $message): void
    {
        /** @var ProductInterface[] $products */
        $products = $this->productRepository->findBy(['id' => $message->getProductIds()]);

        foreach ($products as $product) {
            $callouts = $this->calloutProvider->getCallouts($product);

            $product->getCallouts()->clear();
            $product->setCallouts(new ArrayCollection($callouts));
        }

        $this->productManager->flush();
    }
}
