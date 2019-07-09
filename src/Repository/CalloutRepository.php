<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class CalloutRepository extends EntityRepository implements CalloutRepositoryInterface
{
    public function findActive(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.startsAt IS NULL OR o.startsAt < :date')
            ->andWhere('o.endsAt IS NULL OR o.endsAt > :date')
            ->andWhere('o.enabled = true')
            ->setParameter('date', new \DateTime())
            ->addOrderBy('o.priority', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }
}
