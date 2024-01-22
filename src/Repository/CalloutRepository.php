<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class CalloutRepository extends EntityRepository implements CalloutRepositoryInterface
{
    public function findOneByCode(string $code): ?CalloutInterface
    {
        $obj = $this->findOneBy(['code' => $code]);
        Assert::nullOrIsInstanceOf($obj, CalloutInterface::class);

        return $obj;
    }

    public function findEnabled(array $codes = []): array
    {
        $qb = $this->createQueryBuilder('o')->andWhere('o.enabled = true')->andWhere('SIZE(o.channels) > 0');

        if ([] !== $codes) {
            $qb->andWhere('o.code IN (:codes)')->setParameter('codes', $codes);
        }

        $result = $qb->getQuery()->getResult();

        self::assertResult($result);

        return $result;
    }

    public function findByCodes(array $codes): array
    {
        $objs = $this->findBy(['code' => $codes]);
        Assert::allIsInstanceOf($objs, CalloutInterface::class);

        return $objs;
    }

    /**
     * @psalm-assert list<CalloutInterface> $result
     */
    protected static function assertResult(mixed $result): void
    {
        if (!is_array($result)) {
            throw new \InvalidArgumentException(sprintf('Expected result to be an array, got %s', get_debug_type($result)));
        }

        if (!array_is_list($result)) {
            throw new \InvalidArgumentException('Expected result to be a list');
        }

        Assert::allIsInstanceOf($result, CalloutInterface::class);
    }
}
