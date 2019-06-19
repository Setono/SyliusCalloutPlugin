<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class CalloutRepository extends EntityRepository implements CalloutRepositoryInterface
{
    public function findActive(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.timePeriodStart IS NULL OR o.timePeriodStart < :date')
            ->andWhere('o.timePeriodEnd IS NULL OR o.timePeriodEnd > :date')
            ->andWhere('o.enabled = true')
            ->setParameter('date', new \DateTime())
            ->addOrderBy('o.priority', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }
}
