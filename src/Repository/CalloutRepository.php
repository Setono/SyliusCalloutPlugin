<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use DateTimeInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class CalloutRepository extends EntityRepository implements CalloutRepositoryInterface
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
}
