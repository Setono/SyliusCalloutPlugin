<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use DateTimeInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CalloutRepository extends EntityRepository implements CalloutRepositoryInterface
{
    public function findOrdered(): array
    {
        return $this->createQueryBuilder('o')
            ->addOrderBy('o.priority', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }

    public function hasUpdatedSince(DateTimeInterface $updatedSince): bool
    {
        return $this->_em->createQueryBuilder()
            ->select('count(o.id)')
            ->from($this->getClassName(), 'o')
            ->where('o.updatedAt > :updatedSince')
            ->setParameter('updatedSince', $updatedSince)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function findEligible(?DateTimeInterface $date = null): array
    {
        if (null === $date) {
            $date = new \DateTime();
        }
        $qb = $this->createQueryBuilder('callout');

        return $this->createQueryBuilder('o')
            ->addOrderBy('o.priority', 'desc')
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->lte('o.startsAt', ':date'),
                        $qb->expr()->isNull('o.startsAt'),
                    ),
                    $qb->expr()->orX(
                        $qb->expr()->gte('o.endsAt', ':date'),
                        $qb->expr()->isNull('o.endsAt'),
                    )
                )
            )
            ->andWhere('o.enabled = 1')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }
}
