<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Consumer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutsPlugin\Provider\CalloutProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class ProductCalloutsAssignerConsumer implements ProductCalloutsAssignerConsumerInterface
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

    public function execute(AMQPMessage $message): void
    {
        $body = unserialize($message->getBody());

        Assert::isArray($body);
        Assert::keyExists($body, 'products');

        $products = $this->productRepository->findBy(['id' => $body['products']]);

        /** @var CalloutsAwareInterface $product */
        foreach ($products as $product) {
            Assert::isInstanceOf($product, CalloutsAwareInterface::class, sprintf(
                'Make sure you customized your product to implement %s interface.',
                CalloutsAwareInterface::class
            ));

            $callouts = $this->calloutProvider->getCallouts($product);

            $product->getCallouts()->clear();
            $product->setCallouts(new ArrayCollection($callouts));
        }

        $this->productManager->flush();
    }
}
